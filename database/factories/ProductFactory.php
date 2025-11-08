<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;              
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;              

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $catId = Category::inRandomOrder()->value('id') ?? Category::factory();

        return [
            'category_id' => $catId,
            'name'        => $name,
            'slug'        => Str::slug($name.'-'.Str::random(4)),
            'price'       => $this->faker->numberBetween(200000, 900000),
            'sale_price'  => $this->faker->boolean(35) ? $this->faker->numberBetween(150000, 850000) : null,
            'stock'       => $this->faker->boolean(10) ? 0 : $this->faker->numberBetween(3, 30), // 10% hết hàng
            'image'       => null,
            'description' => $this->faker->sentence(12),
        ];
    }
}
