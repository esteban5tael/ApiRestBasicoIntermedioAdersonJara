<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'category_id' => Category::all()->random(1)->first()->id,
                'brand_id' => Brand::all()->random(1)->first()->id,
                'name' => 'Product :0' . $i,
                'description' => 'Product :0' . $i . ' Description',
                'price' => $i,
                'available_quantity' => 5,
            ]);
        }
    }
}
