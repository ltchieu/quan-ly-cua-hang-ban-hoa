<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['name'=>'Hoa Sinh Nhật',  'description'=>'Bó hoa, giỏ hoa tặng sinh nhật', 'position'=>1],
            ['name'=>'Hoa Khai Trương','description'=>'Kệ hoa/giỏ hoa chúc mừng',       'position'=>2],
            ['name'=>'Hoa Tình Yêu',   'description'=>'Hoa hồng, bó lãng mạn',         'position'=>3],
            ['name'=>'Hoa Chúc Mừng',  'description'=>'Mọi dịp chúc mừng',             'position'=>4],
            ['name'=>'Hoa Chia Buồn',  'description'=>'Kệ hoa chia buồn',              'position'=>5],
            ['name'=>'Bó Hoa Giá Rẻ',  'description'=>'Giá tốt trong ngày',            'position'=>6],
            ['name'=>'Giỏ Hoa',        'description'=>'Giỏ hoa phối lá',               'position'=>7],
            ['name'=>'Hoa Sự Kiện',    'description'=>'Trang trí, đặt theo yêu cầu',   'position'=>8],
        ];
        foreach ($cats as $i => $c) {
            Category::updateOrCreate(
                ['name'=>$c['name']],
                ['description'=>$c['description'] ?? null, 'position'=>$c['position'] ?? $i+1, 'is_active'=>true]
            );
        }
    }
}
