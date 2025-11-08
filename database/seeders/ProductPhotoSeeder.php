<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductPhotoSeeder extends Seeder
{
    public function run(): void
    {
        // bộ ảnh hoa – có thể thêm/bớt tùy ý
        $urls = [
            'https://images.unsplash.com/photo-1509043759401-136742328bb3?q=80&w=1200',
            'https://images.unsplash.com/photo-1475870434830-59d1303f3ac3?q=80&w=1200',
            'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?q=80&w=1200',
            'https://images.unsplash.com/photo-1443181844940-9042ec79924b?q=80&w=1200',
            'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?q=80&w=1200',
            'https://images.unsplash.com/photo-1544957042-1a4e35c12d3a?q=80&w=1200',
            'https://images.unsplash.com/photo-1457089328109-e5d9bd499191?q=80&w=1200',
            'https://images.unsplash.com/photo-1464960200761-731cad9b0dd6?q=80&w=1200',
            'https://images.unsplash.com/photo-1523428461295-92770e70d122?q=80&w=1200',
            'https://images.unsplash.com/photo-1495640452828-3df6795cf69f?q=80&w=1200',
        ];

        $all = Product::all();
        foreach ($all as $p) {
            $p->image = $urls[array_rand($urls)];
            $p->save();
        }
    }
}
