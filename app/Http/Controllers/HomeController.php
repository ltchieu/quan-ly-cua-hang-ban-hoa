<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // danh mục nổi bật (hoặc tất cả is_active, sắp theo position)
        $sections = Category::where('is_active',1)->orderBy('position')->get();

        // SP mới
        $featured = Product::with('category')->latest()->take(8)->get();

        return view('home', [
            'sections' => $sections,
            'featured' => $featured,
        ]);
    }
}
