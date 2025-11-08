<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'content'=>'nullable|string|max:1000',
        ]);
        $data['user_id'] = auth()->id();
        $data['product_id'] = $product->id;

        Review::create($data);
        return back()->with('success','Cảm ơn bạn đã đánh giá!');
    }
}
