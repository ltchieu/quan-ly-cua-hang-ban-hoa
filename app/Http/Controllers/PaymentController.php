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
    public function momo($tempOrderId)
    {
        $sessionData = session()->get($tempOrderId);

        if (!$sessionData || auth()->id() !== $sessionData['user_id']) {
            return redirect()->route('cart.index')->with('error', 'Phiên thanh toán đã hết hạn hoặc không hợp lệ.');
        }

        // Lấy cấu hình từ file config
        $partnerCode = config('payment.momo.partner_code');
        $accessKey = config('payment.momo.access_key'); 
        $secretKey = config('payment.momo.secret_key');    

        $orderInfo = "Thanh toan don hang qua MoMo";
        $amount = (string)$sessionData['total'];
        $orderId = time() . "";
        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = ""; 
        
        // Tạo URL (Ngrok hoặc Domain thật)
        $appUrl = rtrim(config('app.url'), '/');
        $redirectUrl = $appUrl . '/payment/momo/callback';
        $ipnUrl = $appUrl . '/payment/momo/ipn';

        // --- BƯỚC 1: TẠO CHỮ KÝ (SIGNATURE) ---
        $rawSignature = "accessKey=" . $accessKey . 
                        "&amount=" . $amount . 
                        "&extraData=" . $extraData . 
                        "&ipnUrl=" . $ipnUrl . 
                        "&orderId=" . $orderId . 
                        "&orderInfo=" . $orderInfo . 
                        "&partnerCode=" . $partnerCode . 
                        "&redirectUrl=" . $redirectUrl . 
                        "&requestId=" . $requestId . 
                        "&requestType=" . $requestType;

        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

        // --- BƯỚC 2: CHUẨN BỊ DỮ LIỆU GỬI ĐI ---
        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test Store",
            'storeId'     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature
        ];

        // Cache mapping orderId
        cache()->put("momo_order_{$orderId}", $tempOrderId, now()->addHour());

        $client = new \GuzzleHttp\Client();
        
        try {
            // --- BƯỚC 3: GỬI REQUEST ĐẾN MOMO (ENDPOINT CREATE) ---
            $response = $client->post('https://test-payment.momo.vn/v2/gateway/api/create', [
                'json' => $data, 
                'timeout' => 30,
                'verify' => false
            ]);

            $result = json_decode($response->getBody(), true);

            // Log kết quả để debug
            Log::info('Momo Create Payment Response', $result);

            if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                $payUrl = $result['payUrl'];
                
                session()->put("payment_pay_url_{$tempOrderId}", $payUrl);
                
                return redirect()->route('payment.momo.qr', $tempOrderId);
            } else {
                // Xử lý lỗi
                $message = $result['message'] ?? 'Unknown error from MoMo';
                Log::error('Momo Payment Error: ' . $message);
                return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                    ->with('error_message', 'Lỗi MoMo: ' . $message);
            }

        } catch (\Exception $e) {
            Log::error('Momo Exception: ' . $e->getMessage());
            return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                ->with('error_message', 'Không thể kết nối đến cổng thanh toán.');
        }
    }

    /**
     * Momo payment callback.
     */
    public function momoCallback(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $orderIdFromMomo = $request->input('orderId');
        $tempOrderId = cache()->get("momo_order_{$orderIdFromMomo}");

        if (!$tempOrderId) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        if ($resultCode == 0) {
            // Payment successful
            $order = $this->_createOrderFromSession($tempOrderId);
            if (!$order) {
                // If order creation fails, maybe it was already created by IPN.
                // Check the cache for the mapped order ID.
                $orderId = cache()->get("order_id_map_{$tempOrderId}");
                if ($orderId) {
                    return redirect()->route('checkout.success', $orderId);
                }
                return redirect()->route('home')->with('error', 'Lỗi khi xử lý đơn hàng sau khi thanh toán.');
            }
            return redirect()->route('checkout.success', $order->id);
        } else {
            // Payment failed
            $sessionData = session()->get($tempOrderId);
            if ($sessionData && !empty($sessionData['cart'])) {
                session()->put('cart', $sessionData['cart']); // Restore cart
            }
            session()->forget($tempOrderId); // Clean up session
            return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                ->with('error_message', 'Thanh toán không thành công. Giỏ hàng của bạn đã được khôi phục.');
        }
    }

    /**
     * Momo IPN (server-to-server notification).
     */
    public function momoIpn(Request $request)
    {
        $resultCode = $request->input('resultCode');
        $orderIdFromMomo = $request->input('orderId');
        $tempOrderId = cache()->get("momo_order_{$orderIdFromMomo}");

        if ($tempOrderId && $resultCode == 0) {
            // IPN is for confirmation, create order if not already created
            $this->_createOrderFromSession($tempOrderId);
        }

        return response()->json(['message' => 'success']);
    }

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

        $vnp_TmnCode = config('payment.vnpay.tmncode');
        $vnp_HashSecret = config('payment.vnpay.hashsecret');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        
        // Explicitly construct URLs using APP_URL to ensure ngrok URLs are used
        $appUrl = rtrim(config('app.url'), '/');
        $vnp_Returnurl = $appUrl . '/payment/vnpay/callback';

        $vnp_TxnRef = $tempOrderId . '_' . time();
        $vnp_OrderInfo = "Thanh toan don hang Flower Shop";
        $vnp_OrderType = "other";
        $vnp_Amount = $sessionData['total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $hashdata = "";
        $query = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashdata = ltrim($hashdata, '&');
        
        $vnp_Url = $vnp_Url . "?" . $query;
        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $secureHash;

        cache()->put("vnpay_order_{$vnp_TxnRef}", $tempOrderId, now()->addHour());
        session()->put("payment_pay_url_{$tempOrderId}", $vnp_Url);

        return redirect()->route('payment.vnpay.qr', $tempOrderId);
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
                    return redirect()->route('checkout.success', $orderId);
                }
                return redirect()->route('home')->with('error', 'Lỗi khi xử lý đơn hàng sau khi thanh toán.');
            }
            return redirect()->route('checkout.success', $order->id);
        } else {
            // Payment failed
            $sessionData = session()->get($tempOrderId);
            if ($sessionData && !empty($sessionData['cart'])) {
                session()->put('cart', $sessionData['cart']); // Restore cart
            }
            session()->forget($tempOrderId); // Clean up session
            return redirect()->route('payment.error', ['tempOrderId' => $tempOrderId])
                ->with('error_message', 'Thanh toán không thành công. Giỏ hàng của bạn đã được khôi phục.');
        }
    }

    /**
     * Display Momo QR code for payment.
     */
    public function momoQr($tempOrderId)
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
        return view('payment.momo-qr', [
            'qrCodeUrl' => $qrCodeUrl,
            'payUrl' => $payUrl,
            'tempOrderId' => $tempOrderId,
            'total' => $sessionData['total']
        ]);
    }

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
            session()->put('cart', $sessionData['cart']); // Restore cart
        }

        // Clean up all temporary data
        session()->forget($tempOrderId);
        session()->forget("payment_pay_url_{$tempOrderId}");

        return redirect()->route('cart.index')->with('success', 'Thanh toán đã bị hủy. Giỏ hàng của bạn đã được khôi phục. Bạn có thể chọn phương thức thanh toán khác hoặc tiếp tục mua sắm.');
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
            session()->put('cart', $sessionData['cart']);
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
                session()->forget('cart');
                cache()->forget("payment_pay_url_{$tempOrderId}");

                return $order;
            } finally {
                $lock->release();
            }
        }
        return null; // Could not acquire lock
    }
}
