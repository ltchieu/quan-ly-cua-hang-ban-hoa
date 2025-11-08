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
        $cart = session('cart',[]);
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
            'payment_method'=>'required|in:COD'
        ]);

        $cart = session('cart',[]);
        if(empty($cart)) return redirect()->route('cart.index');

        DB::transaction(function () use ($data, $cart) {
            $total = collect($cart)->sum(fn($i)=>$i['price']*$i['qty']);

            $order = Order::create([
                'user_id' => auth()->id(),
                'full_name'=>$data['full_name'],
                'phone'=>$data['phone'],
                'address'=>$data['address'],
                'note'=>$data['note'] ?? null,
                'payment_method'=>$data['payment_method'],
                'status'=>'pending',
                'total'=>$total
            ]);

            foreach($cart as $pid=>$i){
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$pid,
                    'price'=>$i['price'],
                    'quantity'=>$i['qty'],
                ]);
                // trừ tồn kho tối giản
                Product::whereKey($pid)->decrement('stock', $i['qty']);
            }
        });

        session()->forget('cart');
        return redirect()->route('home')->with('success','Đặt hàng thành công!');
    }
}
