<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $cat = $request->input('category');
        $onlyInactive = $request->boolean('inactive', false);

        $products = Product::with('category')
            ->when($q, fn($x)=>$x->where('name','like',"%$q%"))
            ->when($cat, fn($x)=>$x->where('category_id',$cat))
            ->when($onlyInactive, fn($x)=>$x->where('is_active',0))
            ->latest()->paginate(15)->withQueryString();

        $categories = Category::orderBy('position')->get();
        return view('admin.products.index', compact('products','categories','q','cat','onlyInactive'));
    }

    public function create()
    {
        $categories = Category::orderBy('position')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // slug tự tạo nếu bỏ trống
        $data['slug'] = $data['slug'] ?: Str::slug($data['name'].'-'.Str::random(4));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products','public');
        }
        $data['is_active'] = $request->boolean('is_active');

        Product::create($data);
        return redirect()->route('products.index')->with('success','Đã thêm sản phẩm.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('position')->get();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name'].'-'.Str::random(4));
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // xóa ảnh cũ
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success','Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('success','Đã xóa sản phẩm.');
    }
}
