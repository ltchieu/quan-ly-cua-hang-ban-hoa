<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportController extends Controller
{
    /**
     * Show FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'id' => 1,
                'question' => 'Làm thế nào để đặt hàng?',
                'answer' => "Để đặt hàng, hãy:\n1) Duyệt các sản phẩm và chọn hoa bạn muốn,\n2) Thêm vào giỏ hàng,\n3) Tiến hành thanh toán,\n4) Nhập địa chỉ giao hàng,\n5) Chọn phương thức thanh toán (Momo, VNPay hoặc COD),\n6) Hoàn thành đơn hàng.\nBạn sẽ nhận được xác nhận qua email.",
                'category' => 'order'
            ],
            [
                'id' => 2,
                'question' => 'Các phương thức thanh toán nào được hỗ trợ?',
                'answer' => "Chúng tôi hỗ trợ các phương thức thanh toán sau:\n1) Ví điện tử MoMo (QR Code),\n2) Cổng thanh toán VNPay,\n3) Thanh toán khi nhận hàng (COD).\nTất cả phương thức đều an toàn và bảo mật.",
                'category' => 'payment'
            ],
            [
                'id' => 3,
                'question' => 'Thời gian giao hàng bao lâu?',
                'answer' => 'Thời gian giao hàng thường là 1-2 ngày làm việc trong nội thành. Với các khu vực ngoài thành, thời gian có thể kéo dài 2-5 ngày. Bạn sẽ nhận được thông báo cập nhật về trạng thái đơn hàng qua SMS và email.',
                'category' => 'shipping'
            ],
            [
                'id' => 4,
                'question' => 'Có thể hủy đơn hàng không?',
                'answer' => 'Bạn có thể hủy đơn hàng trong vòng 2 giờ sau khi đặt hàng. Sau 2 giờ, đơn hàng sẽ được chuẩn bị giao và không thể hủy. Vui lòng liên hệ với chúng tôi ngay để được hỗ trợ.',
                'category' => 'order'
            ],
            [
                'id' => 5,
                'question' => 'Chính sách đổi trả như thế nào?',
                'answer' => 'Nếu sản phẩm bị hư hỏng hoặc không đúng với mô tả, bạn có thể yêu cầu đổi trả trong vòng 7 ngày. Vui lòng giữ nguyên bao bì và liên hệ với chúng tôi để được hỗ trợ đổi trả miễn phí.',
                'category' => 'return'
            ],
            [
                'id' => 6,
                'question' => 'Các sản phẩm có tươi không?',
                'answer' => 'Tất cả hoa được chọn mới từ vùng trồng được kiểm định. Chúng tôi đảm bảo tất cả sản phẩm đều tươi mới tại thời điểm giao hàng. Nếu không hài lòng, vui lòng liên hệ ngay.',
                'category' => 'product'
            ],
            [
                'id' => 7,
                'question' => 'Có ưu đãi hay khuyến mãi không?',
                'answer' => 'Chúng tôi thường xuyên có các chương trình khuyến mãi, giảm giá theo mùa và các dịp lễ. Bạn có thể sử dụng các mã voucher để giảm giá khi thanh toán. Hãy theo dõi trang chủ để cập nhật các ưu đãi mới nhất.',
                'category' => 'other'
            ],
            [
                'id' => 8,
                'question' => 'Làm thế nào để sử dụng voucher?',
                'answer' => 'Khi thanh toán, hãy nhập mã voucher vào ô "Mã giảm giá" và bấm áp dụng. Hệ thống sẽ tự động tính toán mức giảm giá cho bạn. Mỗi voucher chỉ có thể sử dụng một lần.',
                'category' => 'other'
            ],
        ];

        return view('support.faq', compact('faqs'));
    }

    /**
     * Show contact form page
     */
    public function contact()
    {
        return view('support.contact');
    }

    /**
     * Store contact/support ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:order,product,payment,shipping,return,other',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Generate ticket number before creating
        $ticketNumber = 'TK' . date('YmdHis') . rand(1000, 9999);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'status' => 'open',
            'ticket_number' => $ticketNumber,
        ]);

        return redirect()->route('support.tickets')->with('success', 'Yêu cầu của bạn đã được gửi thành công. Mã yêu cầu: ' . $ticket->ticket_number);
    }

    /**
     * Show user's support tickets
     */
    public function tickets()
    {
        $tickets = auth()->user()->supportTickets()->latest()->paginate(10);
        return view('support.tickets', compact('tickets'));
    }

    /**
     * Show specific ticket detail
     */
    public function show(SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('support.show', compact('ticket'));
    }
}

