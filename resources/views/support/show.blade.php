@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('support.tickets') }}" class="text-pink-600 hover:text-pink-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
            <p class="mt-2 text-gray-600">Mã yêu cầu: <span class="font-mono font-semibold">{{ $ticket->ticket_number }}</span></p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <!-- Status & Info -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Status Badge -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600 text-sm mb-2">Trạng thái</p>
                <div class="text-2xl font-bold
                    @if($ticket->status === 'open') text-red-600
                    @elseif($ticket->status === 'in_progress') text-yellow-600
                    @elseif($ticket->status === 'resolved') text-green-600
                    @else text-gray-600
                    @endif
                ">
                    @if($ticket->status === 'open')
                        <i class="fas fa-clock mr-2"></i>Mở
                    @elseif($ticket->status === 'in_progress')
                        <i class="fas fa-spinner mr-2"></i>Đang xử lý
                    @elseif($ticket->status === 'resolved')
                        <i class="fas fa-check-circle mr-2"></i>Đã giải quyết
                    @else
                        <i class="fas fa-times-circle mr-2"></i>Đã đóng
                    @endif
                </div>
            </div>

            <!-- Category -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600 text-sm mb-2">Danh mục</p>
                <p class="text-lg font-semibold text-gray-900">
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
                </p>
            </div>

            <!-- Created Date -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600 text-sm mb-2">Gửi lúc</p>
                <p class="text-lg font-semibold text-gray-900">{{ $ticket->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600">{{ $ticket->created_at->format('H:i') }}</p>
            </div>

            <!-- Response Date -->
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600 text-sm mb-2">Phản hồi lúc</p>
                @if($ticket->responded_at)
                <p class="text-lg font-semibold text-green-600">{{ $ticket->responded_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600">{{ $ticket->responded_at->format('H:i') }}</p>
                @else
                <p class="text-lg font-semibold text-gray-400">Chưa phản hồi</p>
                @endif
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-lg shadow divide-y">
            <!-- User Message -->
            <div class="p-8">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}&background=random" alt="{{ $ticket->user->name }}" class="w-12 h-12 rounded-full">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->user->name }}</h3>
                            <span class="text-sm text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Yêu cầu hỗ trợ</p>
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-pink-600">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Response -->
            @if($ticket->admin_response)
            <div class="p-8 bg-blue-50">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                            <i class="fas fa-headset"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">Đội hỗ trợ khách hàng</h3>
                            <span class="text-sm text-gray-600">{{ $ticket->responded_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Phản hồi chính thức</p>
                        <div class="bg-white rounded-lg p-4 border-l-4 border-blue-600">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="p-8 text-center">
                <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 mb-4">Chúng tôi đang xử lý yêu cầu của bạn. Vui lòng đợi trong ít phút.</p>
                <p class="text-sm text-gray-500">Thường phản hồi trong 24 giờ</p>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bạn cần giúp gì thêm không?</h3>
            <div class="flex gap-4">
                <a href="{{ route('support.contact') }}" class="flex-1 bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-lg transition text-center">
                    <i class="fas fa-plus mr-2"></i>Gửi yêu cầu mới
                </a>
                <a href="{{ route('support.faq') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition text-center">
                    <i class="fas fa-question-circle mr-2"></i>Xem câu hỏi thường gặp
                </a>
                <a href="{{ route('support.tickets') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition text-center">
                    <i class="fas fa-list mr-2"></i>Xem tất cả yêu cầu
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
