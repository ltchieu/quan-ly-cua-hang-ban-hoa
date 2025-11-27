<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check for direct checkout item first
        $cart = session('direct_checkout_item') ?? session('cart',[]);
        
        abort_if(empty($cart), 302, '', ['Location'=>route('cart.index')]);
        $total = collect($cart)->sum(fn($i)=>$i['price']*$i['qty']);
        return view('checkout.index', compact('cart','total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'=>'required|string|max:100',
            'phone'    =>'required|string|max:20',
            'address'  =>'required|string|max:255',
            'note'     =>'nullable|string|max:500',
            'payment_method'=>'required|in:cod,momo,vnpay'
        ]);

        // Check for direct checkout item first
        $isDirectCheckout = session()->has('direct_checkout_item');
        $cart = session('direct_checkout_item') ?? session('cart',[]);
        
        if(empty($cart)) return redirect()->route('cart.index');

        $total = collect($cart)->sum(fn($i)=>$i['price']*$i['qty']);

        // Handle COD payment directly
        if ($data['payment_method'] === 'cod') {
            $order = DB::transaction(function () use ($data, $cart, $total) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'full_name'=>$data['full_name'],
                    'phone'=>$data['phone'],
                    'address'=>$data['address'],
                    'note'=>$data['note'] ?? null,
                    'payment_method'=>$data['payment_method'],
                    'status'=> 'pending',
                    'total'=>$total
                ]);

                foreach($cart as $pid=>$i){
                    OrderItem::create([
                        'order_id'=>$order->id,
                        'product_id'=>$pid,
                        'price'=>$i['price'],
                        'quantity'=>$i['qty'],
                    ]);
                    Product::whereKey($pid)->decrement('stock', $i['qty']);
                }

                return $order;
            });

            // Only clear the relevant session
            if ($isDirectCheckout) {
                session()->forget('direct_checkout_item');
            } else {
                session()->forget('cart');
            }
            
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công! Vui lòng chờ xác nhận từ cửa hàng.');
        }

        // For online payments, store data in session and redirect to payment gateway
        $tempOrderId = 'order_' . uniqid();
        session()->put($tempOrderId, [
            'order_data' => $data,
            'cart' => $cart,
            'total' => $total,
            'user_id' => auth()->id(),
            'is_direct_checkout' => $isDirectCheckout // Pass this flag to PaymentController
        ]);

        if ($data['payment_method'] === 'momo') {
            return redirect()->route('payment.momo', $tempOrderId);
        } elseif ($data['payment_method'] === 'vnpay') {
            return redirect()->route('payment.vnpay', $tempOrderId);
        }

        // Fallback in case payment method is not handled
        return redirect()->route('cart.index')->with('error', 'Phương thức thanh toán không hợp lệ.');
    }
}
