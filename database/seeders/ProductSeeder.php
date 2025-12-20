<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Razer BlackWidow V3',
                'description' => 'Механическая игровая клавиатура с переключателями Razer Green',
                'price' => 8999,
                'stock' => 15,
                'category' => 'keyboards',
                'brand' => 'Razer',
                'attributes' => [
                    'switch_type' => 'Razer Green',
                    'layout' => 'US',
                    'backlight' => 'RGB',
                    'connection' => 'USB'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'Logitech G Pro X Superlight',
                'description' => 'Беспроводная игровая мышь ультралегкого веса',
                'price' => 12999,
                'stock' => 8,
                'category' => 'mice',
                'brand' => 'Logitech',
                'attributes' => [
                    'sensor' => 'Hero 25K',
                    'weight' => '63g',
                    'buttons' => '5',
                    'battery_life' => '70 часов'
                ],
                'is_featured' => true,
            ],
            [
                'name' => 'SteelSeries Arctis Pro',
                'description' => 'Беспроводные игровые наушники с Hi-Res Audio',
                'price' => 17999,
                'stock' => 5,
                'category' => 'headsets',
                'brand' => 'SteelSeries',
                'attributes' => [
                    'driver_size' => '40mm',
                    'frequency_response' => '10-40000 Hz',
                    'microphone' => 'ClearCast',
                    'wireless' => 'Да'
                ],
                'is_featured' => true,
            ],
        ];

        $userId = \DB::table('users')->value('id') ?? null;
        foreach ($products as $product) {
            $product['user_id'] = $userId;
            Product::create($product);
        }
    }
}