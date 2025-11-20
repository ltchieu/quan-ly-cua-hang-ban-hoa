<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Show the user's vouchers.
     */
    public function index()
    {
        $user = Auth::user();

        // Vouchers are typically system-wide, but we can filter by validity
        $vouchers = Voucher::where('is_active', true)
            ->where('expiry_date', '>=', now())
            ->orderBy('discount_percentage', 'desc')
            ->get();

        return view('vouchers.index', [
            'vouchers' => $vouchers,
        ]);
    }
}
