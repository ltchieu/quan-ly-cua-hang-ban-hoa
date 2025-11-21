@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Liên hệ chúng tôi</h1>
            <p class="mt-2 text-gray-600">Gửi yêu cầu hỗ trợ của bạn đến đội ngũ khách hàng của chúng tôi</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Email -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-pink-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                                <p class="mt-2 text-gray-600">support@flowershop.com</p>
                                <p class="text-sm text-gray-500 mt-1">Phản hồi trong 24 giờ</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone text-pink-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Điện thoại</h3>
                                <p class="mt-2 text-gray-600">1900 1234</p>
                                <p class="text-sm text-gray-500 mt-1">Thứ 2 - Chủ nhật, 8:00 - 22:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-pink-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Địa chỉ</h3>
                                <p class="mt-2 text-gray-600">123 Đường Hoa<br/>Quận 1, TP.HCM</p>
                                <p class="text-sm text-gray-500 mt-1">Thứ 2 - Chủ nhật, 9:00 - 18:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Liên kết nhanh</h3>
                        <div class="space-y-2">
                            <a href="{{ route('support.faq') }}" class="flex items-center text-pink-600 hover:text-pink-700 transition">
                                <i class="fas fa-question-circle mr-2"></i>Xem câu hỏi thường gặp
                            </a>
                            <a href="{{ route('support.tickets') }}" class="flex items-center text-pink-600 hover:text-pink-700 transition">
                                <i class="fas fa-ticket-alt mr-2"></i>Xem yêu cầu của tôi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Gửi yêu cầu hỗ trợ</h2>

                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">Vui lòng kiểm tra lại thông tin:</h3>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('support.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-900">Tiêu đề <span class="text-red-600">*</span></label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                placeholder="Nhập tiêu đề yêu cầu"
                                value="{{ old('subject') }}"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-600 focus:border-transparent @error('subject') border-red-500 @enderror"
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-900">Danh mục <span class="text-red-600">*</span></label>
                            <select 
                                id="category" 
                                name="category"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-600 focus:border-transparent @error('category') border-red-500 @enderror"
                            >
                                <option value="">-- Chọn danh mục --</option>
                                <option value="order" {{ old('category') === 'order' ? 'selected' : '' }}>
                                    <i class="fas fa-shopping-cart"></i> Đơn hàng
                                </option>
                                <option value="product" {{ old('category') === 'product' ? 'selected' : '' }}>
                                    <i class="fas fa-flower"></i> Sản phẩm
                                </option>
                                <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>
                                    <i class="fas fa-credit-card"></i> Thanh toán
                                </option>
                                <option value="shipping" {{ old('category') === 'shipping' ? 'selected' : '' }}>
                                    <i class="fas fa-truck"></i> Giao hàng
                                </option>
                                <option value="return" {{ old('category') === 'return' ? 'selected' : '' }}>
                                    <i class="fas fa-redo"></i> Đổi trả
                                </option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>
                                    <i class="fas fa-question-circle"></i> Khác
                                </option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-900">Chi tiết vấn đề <span class="text-red-600">*</span></label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="8"
                                placeholder="Mô tả chi tiết vấn đề của bạn (tối thiểu 10 ký tự)..."
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-600 focus:border-transparent resize-none @error('message') border-red-500 @enderror"
                            >{{ old('message') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="char-count">0</span>/2000 ký tự
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button 
                                type="submit" 
                                class="flex-1 bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-lg transition"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Gửi yêu cầu
                            </button>
                            <button 
                                type="reset" 
                                class="px-6 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition"
                            >
                                <i class="fas fa-redo mr-2"></i>Xóa
                            </button>
                        </div>

                        <!-- Info -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Chúng tôi sẽ phản hồi yêu cầu của bạn trong 24 giờ. Vui lòng kiểm tra email để cập nhật.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Character counter
    const messageField = document.getElementById('message');
    const charCount = document.querySelector('.char-count');
    
    messageField.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
@endsection
