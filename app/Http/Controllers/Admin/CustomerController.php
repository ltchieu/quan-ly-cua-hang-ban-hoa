<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount('orders');

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->is_admin) {
            abort(404);
        }

        $customer->load(['orders' => function($query) {
            $query->latest()->with('items.product');
        }]);

        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders' => $customer->orders->where('status', 'pending')->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function destroy(User $customer)
    {
        if ($customer->is_admin) {
            abort(404);
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}
