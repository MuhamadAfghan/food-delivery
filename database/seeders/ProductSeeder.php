<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['name' => 'Chicken Teriyaki Bowl'],
            [
                'price' => 32000,
                'description' => 'Grilled chicken, teriyaki glaze, crunchy greens, and warm rice.',
                'image_path' => 'images/chicken-teriyaki.png',
                'is_active' => true,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Spicy Tuna Poke Bowl'],
            [
                'price' => 38000,
                'description' => 'Fresh tuna, spicy mayo, cucumber, edamame, and sesame.',
                'image_path' => 'images/spicy-tuna.png',
                'is_active' => true,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Veggie Crunch Bowl'],
            [
                'price' => 29000,
                'description' => 'Colorful veggies, roasted corn, beans, and citrus dressing.',
                'image_path' => 'images/veggie-crunch.png',
                'is_active' => true,
            ]
        );
    }
}
