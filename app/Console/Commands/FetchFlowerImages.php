<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use App\Models\Category;

class FetchFlowerImages extends Command
{
    protected $signature = 'images:fetch-flowers {--clear : Xóa hết ảnh cũ trước khi tải mới}';
    protected $description = 'Tải ảnh hoa từ hoatuoihuythao.com theo loại và cập nhật vào database';

    protected $categoryUrls = [
        'Hoa Sinh Nhật' => 'https://hoatuoihuythao.com/san-pham/hoa-sinh-nhat-y-nghia-gia-re.html',
        'Hoa Khai Trương' => 'https://hoatuoihuythao.com/san-pham/hoa-khai-truong.html',
        'Hoa Tình Yêu' => 'https://hoatuoihuythao.com/san-pham/hoa-tinh-yeu.html',
        'Hoa Chúc Mừng' => 'https://hoatuoihuythao.com/san-pham/hoa-chuc-mung.html',
        'Hoa Chia Buồn' => 'https://hoatuoihuythao.com/san-pham/hoa-chia-buon.html',
        'Bó Hoa Giá Rẻ' => 'https://hoatuoihuythao.com/san-pham/bo-hoa-gia-re.html',
        'Giỏ Hoa' => 'https://hoatuoihuythao.com/san-pham/gio-hoa.html',
        'Hoa Sự Kiện' => 'https://hoatuoihuythao.com/san-pham/hoa-su-kien.html',
    ];

    protected $fallbackImages = [
        'Hoa Sinh Nhật' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?w=600&h=400&fit=crop',
        'Hoa Khai Trương' => 'https://images.unsplash.com/photo-1563241527-3004d8c37e0a?w=600&h=400&fit=crop',
        'Hoa Tình Yêu' => 'https://images.unsplash.com/photo-1516241816346-4cbf8fbb9cf8?w=600&h=400&fit=crop',
        'Hoa Chúc Mừng' => 'https://images.unsplash.com/photo-1534357582772-f67570beb831?w=600&h=400&fit=crop',
        'Hoa Chia Buồn' => 'https://images.unsplash.com/photo-1486316879144-c9b55c77c2c5?w=600&h=400&fit=crop',
        'Bó Hoa Giá Rẻ' => 'https://images.unsplash.com/photo-1487180144351-b8472da7d491?w=600&h=400&fit=crop',
        'Giỏ Hoa' => 'https://images.unsplash.com/photo-1469022563149-aa64dbd37dae?w=600&h=400&fit=crop',
        'Hoa Sự Kiện' => 'https://images.unsplash.com/photo-1517291900032-b6881500f91e?w=600&h=400&fit=crop',
    ];

    public function handle()
    {
        $directory = storage_path('app/public/images');
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        @chmod($directory, 0755);
        
        if ($this->option('clear')) {
            $this->info("🗑️  Đang xóa ảnh cũ...");
            File::deleteDirectory($directory);
            File::makeDirectory($directory, 0755, true);
            $this->info("✓ Ảnh cũ đã được xóa.\n");
        }

        $categories = Category::with('products')->get();
        $totalProducts = 0;
        $successCount = 0;
        $errorCount = 0;

        if ($categories->isEmpty()) {
            $this->error('❌ Không tìm thấy danh mục.');
            return;
        }

        $this->info("📊 Tìm thấy " . $categories->count() . " danh mục hoa.\n");

        foreach ($categories as $category) {
            $products = $category->products;
            $count = $products->count();
            $totalProducts += $count;

            if ($count === 0) {
                $this->warn("⚠️  Danh mục '{$category->name}' không có sản phẩm.");
                continue;
            }

            $this->line("\n🌸 Đang xử lý danh mục: <fg=cyan>{$category->name}</> ({$count} sản phẩm)");
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $categoryImages = $this->fetchCategoryImages($category->name);
            
            if (empty($categoryImages)) {
                $this->warn("   ⚠️  Không tìm được ảnh từ website, sử dụng fallback");
                $categoryImages = [$this->fallbackImages[$category->name] ?? $this->fallbackImages['Hoa Sinh Nhật']];
            }

            foreach ($products as $index => $product) {
                try {
                    $imageUrl = $categoryImages[$index % count($categoryImages)];
                    
                    $context = stream_context_create([
                        'http' => [
                            'timeout' => 15,
                            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                        ],
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ]);
                    
                    $imageContent = @file_get_contents($imageUrl, false, $context);

                    if (!$imageContent || strlen($imageContent) < 5000) {
                        $fallbackUrl = $this->fallbackImages[$category->name] ?? $this->fallbackImages['Hoa Sinh Nhật'];
                        $imageContent = @file_get_contents($fallbackUrl, false, $context);
                    }

                    if ($imageContent && strlen($imageContent) > 5000) {
                        $filename = 'flower_' . $product->id . '_' . time() . '.jpg';
                        $savePath = $directory . '/' . $filename;

                        File::put($savePath, $imageContent);
                        @chmod($savePath, 0644);

                        // Lưu đường dẫn tương đối tính từ storage/app/public
                        $product->image = 'images/' . $filename;
                        $product->save();

                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                }

                $bar->advance();
                usleep(300000);
            }

            $bar->finish();
        }

        $this->newLine(2);
        $this->info("✅ Hoàn tát!");
        $this->line("📊 Kết quả:");
        $this->line("  • Tổng sản phẩm: {$totalProducts}");
        $this->line("  • Thành công: <fg=green>{$successCount}</> ✓");
        $this->line("  • Lỗi: <fg=red>{$errorCount}</> ✗");
        $this->line("💾 Ảnh được lưu tại: {$directory}");
    }

    private function fetchCategoryImages($categoryName)
    {
        try {
            $url = $this->categoryUrls[$categoryName] ?? null;
            
            if (!$url) {
                return [];
            }

            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $html = @file_get_contents($url, false, $context);
            
            if (!$html) {
                return [];
            }

            $images = [];
            
            // Parse img src attributes
            if (preg_match_all('/<img[^>]*src=["\'](.*?)["\'][^>]*>/i', $html, $matches)) {
                foreach ($matches[1] as $imgUrl) {
                    if (preg_match('/(product|hoa|san-pham|uploads).*\.(jpg|jpeg|png|webp)/i', $imgUrl)) {
                        if (strpos($imgUrl, 'http') === false) {
                            $imgUrl = 'https://hoatuoihuythao.com' . (strpos($imgUrl, '/') === 0 ? '' : '/') . $imgUrl;
                        }
                        
                        if (!in_array($imgUrl, $images)) {
                            $images[] = $imgUrl;
                        }
                    }
                }
            }

            // Parse data-src for lazy-loaded images
            if (preg_match_all('/data-src=["\'](.*?)["\']/', $html, $matches)) {
                foreach ($matches[1] as $imgUrl) {
                    if (preg_match('/(product|hoa|san-pham|uploads).*\.(jpg|jpeg|png|webp)/i', $imgUrl)) {
                        if (strpos($imgUrl, 'http') === false) {
                            $imgUrl = 'https://hoatuoihuythao.com' . (strpos($imgUrl, '/') === 0 ? '' : '/') . $imgUrl;
                        }
                        
                        if (!in_array($imgUrl, $images)) {
                            $images[] = $imgUrl;
                        }
                    }
                }
            }

            return array_slice($images, 0, 10);
            
        } catch (\Exception $e) {
            return [];
        }
    }
}


