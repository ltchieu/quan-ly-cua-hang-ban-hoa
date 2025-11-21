<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
                'min_total' => 0,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'usage_limit' => 100,
                'used' => 5,
                'is_active' => true,
                'expiry_date' => now()->addMonths(3),
                'discount_percentage' => 10,
                'description' => 'Giảm 10% cho khách hàng mới'
            ],
            [
                'code' => 'SAVE100',
                'type' => 'fixed',
                'value' => 100000,
                'min_total' => 500000,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(2),
                'usage_limit' => 50,
                'used' => 8,
                'is_active' => true,
                'expiry_date' => now()->addMonths(2),
                'discount_percentage' => 0,
                'description' => 'Giảm 100k khi mua từ 500k'
            ],
            [
                'code' => 'VIP20',
                'type' => 'percent',
                'value' => 20,
                'min_total' => 1000000,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(1),
                'usage_limit' => 30,
                'used' => 2,
                'is_active' => true,
                'expiry_date' => now()->addMonths(1),
                'discount_percentage' => 20,
                'description' => 'Giảm 20% cho đơn hàng trên 1 triệu'
            ],
            [
                'code' => 'BIRTHDAY30',
                'type' => 'percent',
                'value' => 30,
                'min_total' => 200000,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(1),
                'usage_limit' => 200,
                'used' => 45,
                'is_active' => true,
                'expiry_date' => now()->addMonths(1),
                'discount_percentage' => 30,
                'description' => 'Giảm 30% cho hoa sinh nhật'
            ],
            [
                'code' => 'LOVE50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_total' => 300000,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'usage_limit' => 150,
                'used' => 12,
                'is_active' => true,
                'expiry_date' => now()->addMonths(3),
                'discount_percentage' => 0,
                'description' => 'Giảm 50k khi mua hoa tình yêu'
            ],
            [
                'code' => 'EXPIRED2024',
                'type' => 'percent',
                'value' => 50,
                'min_total' => 100000,
                'starts_at' => now()->subMonths(3),
                'ends_at' => now()->subDays(1),
                'usage_limit' => 100,
                'used' => 100,
                'is_active' => false,
                'expiry_date' => now()->subDays(1),
                'discount_percentage' => 50,
                'description' => 'Mã đã hết hạn'
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(
                ['code' => $voucher['code']],
                $voucher
            );
        }

        $this->command->info('✓ Đã tạo ' . count($vouchers) . ' mã giảm giá test');
    }
}
