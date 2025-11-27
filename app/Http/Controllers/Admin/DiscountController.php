<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::query();
        
        if ($request->filled('q')) {
            $query->where('code', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function($q) {
                          $q->whereNull('ends_at')
                            ->orWhere('ends_at', '>', now());
                      });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('ends_at', '<', now());
            }
        }
        
        $vouchers = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.discounts.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|integer|min:0',
            'min_total' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['used'] = 0;

        Voucher::create($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function edit(Voucher $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Voucher $discount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $discount->id,
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|integer|min:0',
            'min_total' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được cập nhật!');
    }

    public function destroy(Voucher $discount)
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Mã giảm giá đã được xóa!');
    }
}
