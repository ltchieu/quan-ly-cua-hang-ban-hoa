@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">Trung tâm Trợ giúp</h1>
            <p class="mt-2 text-gray-600">Tìm câu trả lời cho các câu hỏi thường gặp</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar - Categories -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow sticky top-4">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
                        <div class="space-y-2">
                            <button onclick="filterFAQ('all')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn active" data-filter="all">
                                <i class="fas fa-list mr-2"></i>Tất cả câu hỏi
                            </button>
                            <button onclick="filterFAQ('order')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="order">
                                <i class="fas fa-shopping-cart mr-2"></i>Đơn hàng
                            </button>
                            <button onclick="filterFAQ('payment')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="payment">
                                <i class="fas fa-credit-card mr-2"></i>Thanh toán
                            </button>
                            <button onclick="filterFAQ('shipping')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="shipping">
                                <i class="fas fa-truck mr-2"></i>Giao hàng
                            </button>
                            <button onclick="filterFAQ('return')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="return">
                                <i class="fas fa-redo mr-2"></i>Đổi trả
                            </button>
                            <button onclick="filterFAQ('product')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="product">
                                <i class="fas fa-flower mr-2"></i>Sản phẩm
                            </button>
                            <button onclick="filterFAQ('other')" class="w-full text-left px-4 py-2 rounded text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition filter-btn" data-filter="other">
                                <i class="fas fa-question-circle mr-2"></i>Khác
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contact Support Button -->
                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Không tìm thấy câu trả lời?</h3>
                    <a href="{{ route('support.contact') }}" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 rounded-lg transition">
                        <i class="fas fa-envelope mr-2"></i>Liên hệ chúng tôi
                    </a>
                </div>
            </div>

            <!-- Main Content - FAQ -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow">
                    <div class="divide-y">
                        @foreach($faqs as $faq)
                        <div class="faq-item p-6 border-l-4 border-transparent hover:border-pink-600 transition" data-category="{{ $faq['category'] }}">
                            <details class="group cursor-pointer">
                                <summary class="flex items-center justify-between font-semibold text-gray-900 hover:text-pink-600 transition">
                                    <div class="flex items-start gap-3">
                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-pink-100 text-pink-600 text-sm font-semibold flex-shrink-0">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span>{{ $faq['question'] }}</span>
                                    </div>
                                </summary>
                                <div class="mt-4 ml-9 text-gray-700 leading-relaxed">
                                    {!! nl2br(e($faq['answer'])) !!}
                                </div>
                            </details>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .filter-btn.active {
        @apply bg-pink-600 text-white;
    }

    .faq-item.hidden {
        display: none;
    }

    details > summary::-webkit-details-marker {
        display: none;
    }

    details summary i {
        transition: transform 0.3s ease;
    }

    details[open] summary i {
        transform: rotate(45deg);
    }
</style>

<script>
    function filterFAQ(category) {
        const items = document.querySelectorAll('.faq-item');
        const buttons = document.querySelectorAll('.filter-btn');

        // Update active button
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === category) {
                btn.classList.add('active');
            }
        });

        // Filter items
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }
</script>
@endsection
