<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    /**
     * Show the user's order history with filtering.
     */
    public function index()
    {
        $status = request('status', 'all');
        $sortBy = request('sort', 'latest');

        $query = Order::where('user_id', Auth::id())
            ->with('items.product');

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Sort
        if ($sortBy === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        $orders = $query->paginate(10);

        // Count orders by status for tabs
        $statusCounts = Order::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allCount = Order::where('user_id', Auth::id())->count();

        return view('orders.history', [
            'orders' => $orders,
            'status' => $status,
            'sortBy' => $sortBy,
            'statusCounts' => $statusCounts,
            'allCount' => $allCount,
        ]);
    }

    /**
     * Show a specific order detail.
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->findOrFail($id);

        return view('orders.show', [
            'order' => $order,
        ]);
    }
}
