<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);    
        // 2) tạo 80 sản phẩm rải đều
        \App\Models\Product::factory(80)->create();
    
        //\App\Models\Category::factory()->count(5)->hasProducts(12)->create();
        // tạo 1 admin
        \App\Models\User::factory()->create([
          'name'=>'Admin', 'email'=>'admin@example.com', 'password'=>bcrypt('password'),
          'is_admin'=>true
        ]);
    }
}
