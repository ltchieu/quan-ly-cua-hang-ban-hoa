<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function cart() { return session()->get('cart', []); }
    protected function save($cart){ session(['cart'=>$cart]); }

    public function index()
    {
        $cart = $this->cart();
        $total = collect($cart)->sum(fn($i)=> ($i['price']*$i['qty']));
        return view('cart.index', compact('cart','total'));
    }

    public function add(Request $request, Product $product)
    {
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
}
