<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q   = $request->input('q');
        $cat = $request->input('category');
        $pmin= $request->input('pmin');
        $pmax= $request->input('pmax');
        $sort= $request->input('sort'); // price_asc|price_desc|new

        $products = Product::with('category')
            ->when($q, fn($x)=>$x->where('name','like',"%$q%"))
            ->when($cat, fn($x)=>$x->where('category_id',$cat))
            ->when($pmin && $pmax, fn($x)=>$x->whereBetween('price',[(int)$pmin,(int)$pmax]))
            ->when($sort==='price_asc', fn($x)=>$x->orderBy('price'))
            ->when($sort==='price_desc', fn($x)=>$x->orderByDesc('price'))
            ->when($sort==='new' || !$sort, fn($x)=>$x->latest())
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        return view('products.index', compact('products','categories','q','cat','pmin','pmax','sort'));
    }

    public function show(Product $product)
    {
        $product->load('category','reviews.user');
        $avg = round((float)$product->reviews()->avg('rating'),1);
        return view('products.show', compact('product','avg'));
    }
}
