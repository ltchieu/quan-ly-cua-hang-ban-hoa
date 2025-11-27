<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function cart() { return session()->get('cart', []); }
    protected function save($cart){ session(['cart'=>$cart]); }

    public function index()
    {
        // Clear direct checkout item if user visits cart
        session()->forget('direct_checkout_item');
        
        $cart = $this->cart();
        $total = collect($cart)->sum(fn($i)=> ($i['price']*$i['qty']));
        $appliedVoucher = session()->get('applied_voucher');
        
        $discount = 0;
        $finalTotal = $total;
        
        if ($appliedVoucher) {
            $voucher = Voucher::where('code', $appliedVoucher['code'])->first();
            if ($voucher) {
                $discount = $this->calculateDiscount($total, $voucher);
                $finalTotal = $total - $discount;
            }
        }
        
        return view('cart.index', compact('cart', 'total', 'discount', 'finalTotal', 'appliedVoucher'));
    }

    public function add(Request $request, Product $product)
    {
        // Clear direct checkout item if user adds to main cart
        session()->forget('direct_checkout_item');

        $qty = max(1, (int)$request->input('qty', 1));
        $price = $product->sale_price ?? $product->price;

        $cart = $this->cart();
        if(isset($cart[$product->id])){
            $cart[$product->id]['qty'] += $qty;
        }else{
            $cart[$product->id] = [
                'name'=>$product->name,
                'price'=>$price,
                'qty'=>$qty,
                'image'=>$product->image
            ];
        }
        $this->save($cart);
        return back()->with('success','Đã thêm vào giỏ hàng');
    }

    public function buyNow(Request $request, Product $product)
    {
        $qty = max(1, (int)$request->input('qty', 1));
        $price = $product->sale_price ?? $product->price;

        // Store in a separate session key for direct checkout
        $directItem = [
            $product->id => [
                'name'=>$product->name,
                'price'=>$price,
                'qty'=>$qty,
                'image'=>$product->image
            ]
        ];
        
        session(['direct_checkout_item' => $directItem]);
        
        return redirect()->route('checkout.index');
    }

    public function update(Request $request, Product $product)
    {
        $qty = max(1, (int)$request->input('qty', 1));
        $cart = $this->cart();
        if(isset($cart[$product->id])){
            $cart[$product->id]['qty'] = $qty;
        }
        $this->save($cart);
        return back();
    }

    public function remove(Product $product)
    {
        $cart = $this->cart();
        unset($cart[$product->id]);
        $this->save($cart);
        return back();
    }

    /**
     * Apply voucher code to cart
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50'
        ]);

        $code = strtoupper($request->voucher_code);
        $cart = $this->cart();

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống'], 422);
        }

        $total = collect($cart)->sum(fn($i) => ($i['price'] * $i['qty']));
        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->where('expiry_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'], 422);
        }

        // Check usage limit
        if ($voucher->usage_limit && $voucher->used >= $voucher->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'], 422);
        }

        // Check minimum order value
        if ($total < $voucher->min_total) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu: ' . number_format($voucher->min_total, 0, '.', ',') . '₫'
            ], 422);
        }

        $discount = $this->calculateDiscount($total, $voucher);
        $finalTotal = $total - $discount;

        // Store voucher in session
        session([
            'applied_voucher' => [
                'code' => $voucher->code,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'discount' => $discount
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'discount' => number_format($discount, 0, '.', ','),
            'final_total' => number_format($finalTotal, 0, '.', ','),
            'discount_amount' => $discount
        ]);
    }

    /**
     * Remove applied voucher
     */
    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        
        $cart = $this->cart();
        $total = collect($cart)->sum(fn($i) => ($i['price'] * $i['qty']));

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa mã giảm giá',
            'total' => number_format($total, 0, '.', ',')
        ]);
    }

    /**
     * Calculate discount amount
     */
    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->type === 'percent') {
            return ($total * $voucher->value) / 100;
        } else {
            return min($voucher->value, $total);
        }
    }
}
