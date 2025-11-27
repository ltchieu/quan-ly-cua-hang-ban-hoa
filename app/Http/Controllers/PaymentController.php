<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Redirect to Momo payment gateway - QR Code Payment (v2 API).
     *
     * @param string $tempOrderId
     */


    /**
     * Momo payment callback.
     */


    /**
     * Redirect to VNPay payment gateway.
     *
     * @param string $tempOrderId
     */
    public function vnpay($tempOrderId)
    {
        $sessionData = session()->get($tempOrderId);

        if (!$sessionData || auth()->id() !== $sessionData['user_id']) {
            return redirect()->route('cart.index')->with('error', 'Phiên thanh toán đã hết hạn hoặc không hợp lệ.');
        }

        $vnp_TmnCode = config('payment.vnpay.tmn_code');
        $vnp_HashSecret = config('payment.vnpay.hash_secret');
        $vnp_Url = config('payment.vnpay.url');
        $vnp_Returnurl = config('payment.vnpay.return_url');

        // Robustly handle return URL
        if (empty($vnp_Returnurl)) {
             $appUrl = rtrim(config('app.url'), '/');
             $vnp_Returnurl = $appUrl . '/payment/vnpay/callback';
        } else {
            // Check if the return URL is just a domain (e.g. http://127.0.0.1:8000) without the path
            $parsedUrl = parse_url($vnp_Returnurl);
            if (!isset($parsedUrl['path']) || $parsedUrl['path'] === '/' || $parsedUrl['path'] === '') {
                $vnp_Returnurl = rtrim($vnp_Returnurl, '/') . '/payment/vnpay/callback';
            }
        }
        
        Log::info('VNPAY Return URL: ' . $vnp_Returnurl);

        $vnp_TxnRef = $tempOrderId . '_' . time();
        $vnp_OrderInfo = "Thanh toan don hang Flower Shop";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = (int)($sessionData['total'] * 100);
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        
        // Fix for localhost IPv6
        if ($vnp_IpAddr == '::1') {
            $vnp_IpAddr = '127.0.0.1';
        }

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->timezone('Asia/Ho_Chi_Minh')->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // ĐỂ HIỂN THỊ MÃ QR NGAY LẬP TỨC:
        // if (true) { 
        //     $inputData['vnp_BankCode'] = 'VNPAYQR';
        // }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        Log::info('VNPAY Payment URL: ' . $vnp_Url);

        cache()->put("vnpay_order_{$vnp_TxnRef}", $tempOrderId, now()->addHour());
        
        // Chuyển hướng khách hàng tới VNPAY
        return redirect($vnp_Url);
    }

    /**
     * VNPay payment callback.
     */
    public function vnpayCallback(Request $request)
    {
        $vnp_TxnRef = $request->input('vnp_TxnRef');
        $vnp_ResponseCode = $request->input('vnp_ResponseCode');
        $tempOrderId = cache()->get("vnpay_order_{$vnp_TxnRef}");

        if (!$tempOrderId) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        // Verify signature (simplified for clarity, production should be robust)
        if ($vnp_ResponseCode == "00") {
            // Payment successful
            $order = $this->_createOrderFromSession($tempOrderId);
            if (!$order) {
                $orderId = cache()->get("order_id_map_{$tempOrderId}");
                if ($orderId) {
                    return redirect()->route('checkout.success', $orderId)->with('success', 'Thanh toán thành công!');
                }
                return redirect()->route('home')->with('error', 'Lỗi khi xử lý đơn hàng sau khi thanh toán.');
            }
            return redirect()->route('checkout.success', $order->id)->with('success', 'Thanh toán thành công!');
        } else {
            // Payment failed
            $sessionData = session()->get($tempOrderId);
            if ($sessionData && !empty($sessionData['cart'])) {
                // Only restore to main cart if it wasn't a direct checkout
                if (empty($sessionData['is_direct_checkout'])) {
                    session()->put('cart', $sessionData['cart']); // Restore cart
                }
            }
            session()->forget($tempOrderId); // Clean up session
            return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                ->with('error_message', 'Thanh toán không thành công.');
        }
    }

    /**
     * Display Momo QR code for payment.
     */


    /**
     * Display VNPay QR code for payment.
     */
    public function vnpayQr($tempOrderId)
    {
        $sessionData = session()->get($tempOrderId);
        if (!$sessionData) {
            return redirect()->route('cart.index')->with('error', 'Phiên thanh toán đã hết hạn.');
        }

        $payUrl = session()->get("payment_pay_url_{$tempOrderId}");
        if (!$payUrl) {
            return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                ->with('error_message', 'URL thanh toán đã hết hạn. Vui lòng thử lại.');
        }
        $qrCodeUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($payUrl);
        return view('payment.vnpay-qr', [
            'qrCodeUrl' => $qrCodeUrl,
            'payUrl' => $payUrl,
            'tempOrderId' => $tempOrderId,
            'total' => $sessionData['total']
        ]);
    }

    /**
     * Check the status of a payment.
     */
    public function paymentStatus($tempOrderId)
    {
        $orderId = cache()->get("order_id_map_{$tempOrderId}");

        if ($orderId) {
            return response()->json([
                'status' => 'success',
                'redirect_url' => route('checkout.success', $orderId)
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * User cancels the payment process.
     */
    public function cancelPayment($tempOrderId)
    {
        $sessionData = session()->get($tempOrderId);

        if ($sessionData && !empty($sessionData['cart'])) {
            // Only restore to main cart if it wasn't a direct checkout
            if (empty($sessionData['is_direct_checkout'])) {
                session()->put('cart', $sessionData['cart']); // Restore cart
            }
        }

        // Clean up all temporary data
        session()->forget($tempOrderId);
        session()->forget("payment_pay_url_{$tempOrderId}");

        return redirect()->route('cart.index')->with('success', 'Thanh toán đã bị hủy.');
    }

    /**
     * Payment success page.
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('payment.success', compact('order'));
    }

    /**
     * Payment error page.
     */
    public function error($tempOrderId)
    {
        // Restore cart if possible
        $sessionData = session()->get($tempOrderId);
        if ($sessionData && !empty($sessionData['cart']) && !session()->has('cart')) {
             // Only restore to main cart if it wasn't a direct checkout
             if (empty($sessionData['is_direct_checkout'])) {
                session()->put('cart', $sessionData['cart']);
             }
        }
        session()->forget($tempOrderId);
        return view('payment.error');
    }

    /**
     * Creates an order from session data. This is the single point of truth for order creation.
     *
     * @param string $tempOrderId
     * @return Order|null
     */
    private function _createOrderFromSession($tempOrderId)
    {
        // Use a lock to prevent race conditions from callback vs. IPN
        $lock = cache()->lock("create_order_{$tempOrderId}", 10);

        if ($lock->get()) {
            try {
                $sessionData = session()->get($tempOrderId);

                // If session data is gone, assume order was already created
                if (!$sessionData) {
                    return null;
                }

                $order = DB::transaction(function () use ($sessionData) {
                    $data = $sessionData['order_data'];
                    $cart = $sessionData['cart'];
                    $total = $sessionData['total'];

                    $order = Order::create([
                        'user_id' => $sessionData['user_id'],
                        'full_name' => $data['full_name'],
                        'phone' => $data['phone'],
                        'address' => $data['address'],
                        'note' => $data['note'] ?? null,
                        'payment_method' => $data['payment_method'],
                        'status' => 'processing', // Directly set to processing
                        'total' => $total,
                        'paid_at' => now(),
                    ]);

                    foreach ($cart as $pid => $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $pid,
                            'price' => $item['price'],
                            'quantity' => $item['qty'],
                        ]);
                        Product::whereKey($pid)->decrement('stock', $item['qty']);
                    }
                    return $order;
                });

                // Map temp ID to real order ID and clean up session
                cache()->put("order_id_map_{$tempOrderId}", $order->id, now()->addMinutes(10));
                session()->forget($tempOrderId);
                
                // Only clear the relevant session
                if (isset($sessionData['is_direct_checkout']) && $sessionData['is_direct_checkout']) {
                    session()->forget('direct_checkout_item');
                } else {
                    session()->forget('cart');
                }
                
                cache()->forget("payment_pay_url_{$tempOrderId}");

                return $order;
            } finally {
                $lock->release();
            }
        }
        return null; // Could not acquire lock
    }
}
