@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Yêu cầu hỗ trợ của tôi</h1>
                    <p class="mt-2 text-gray-600">Theo dõi trạng thái các yêu cầu của bạn</p>
                </div>
                <a href="{{ route('support.contact') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Gửi yêu cầu mới
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        @if ($tickets->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có yêu cầu nào</h3>
            <p class="text-gray-600 mb-6">Bạn chưa gửi yêu cầu hỗ trợ nào. Nếu bạn cần giúp đỡ, vui lòng liên hệ với chúng tôi.</p>
            <a href="{{ route('support.contact') }}" class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                <i class="fas fa-envelope mr-2"></i>Gửi yêu cầu ngay
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach ($tickets as $ticket)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition cursor-pointer" onclick="window.location.href='{{ route('support.show', $ticket->id) }}'">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <!-- Ticket Number & Category Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $ticket->ticket_number }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($ticket->category === 'order') bg-blue-100 text-blue-800
                                    @elseif($ticket->category === 'payment') bg-green-100 text-green-800
                                    @elseif($ticket->category === 'shipping') bg-purple-100 text-purple-800
                                    @elseif($ticket->category === 'return') bg-orange-100 text-orange-800
                                    @elseif($ticket->category === 'product') bg-pink-100 text-pink-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    @if($ticket->category === 'order')
                                        <i class="fas fa-shopping-cart mr-1"></i>Đơn hàng
                                    @elseif($ticket->category === 'payment')
                                        <i class="fas fa-credit-card mr-1"></i>Thanh toán
                                    @elseif($ticket->category === 'shipping')
                                        <i class="fas fa-truck mr-1"></i>Giao hàng
                                    @elseif($ticket->category === 'return')
                                        <i class="fas fa-redo mr-1"></i>Đổi trả
                                    @elseif($ticket->category === 'product')
                                        <i class="fas fa-flower mr-1"></i>Sản phẩm
                                    @else
                                        <i class="fas fa-question-circle mr-1"></i>Khác
                                    @endif
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($ticket->status === 'open') bg-red-100 text-red-800
                                    @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    @if($ticket->status === 'open')
                                        <i class="fas fa-clock mr-1"></i>Mở
                                    @elseif($ticket->status === 'in_progress')
                                        <i class="fas fa-spinner mr-1"></i>Đang xử lý
                                    @elseif($ticket->status === 'resolved')
                                        <i class="fas fa-check-circle mr-1"></i>Đã giải quyết
                                    @else
                                        <i class="fas fa-times-circle mr-1"></i>Đã đóng
                                    @endif
                                </span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                {{ $ticket->subject }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                {{ Str::limit($ticket->message, 100) }}
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span>
                                    <i class="far fa-calendar mr-1"></i>{{ $ticket->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if($ticket->responded_at)
                                <span class="text-green-600">
                                    <i class="fas fa-check mr-1"></i>Đã phản hồi: {{ $ticket->responded_at->format('d/m/Y H:i') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4 text-gray-400">
                            <i class="fas fa-chevron-right text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $tickets->links() }}
        </div>
        @endif

        <!-- FAQ & Quick Links -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-question-circle text-4xl text-pink-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Câu hỏi thường gặp</h3>
                <p class="text-gray-600 text-sm mb-4">Tìm câu trả lời cho các câu hỏi thường gặp</p>
                <a href="{{ route('support.faq') }}" class="text-pink-600 hover:text-pink-700 font-semibold transition">
                    Xem FAQ →
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-envelope text-4xl text-pink-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Liên hệ chúng tôi</h3>
                <p class="text-gray-600 text-sm mb-4">Không tìm được giải pháp? Hãy liên hệ với đội support</p>
                <a href="{{ route('support.contact') }}" class="text-pink-600 hover:text-pink-700 font-semibold transition">
                    Gửi yêu cầu →
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6 text-center">
                <i class="fas fa-phone text-4xl text-pink-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Gọi cho chúng tôi</h3>
                <p class="text-gray-600 text-sm mb-4">1900 1234 - Thứ 2-7, 8:00 - 22:00</p>
                <a href="tel:19001234" class="text-pink-600 hover:text-pink-700 font-semibold transition">
                    Gọi ngay →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
