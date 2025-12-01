<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DrinkPrice;

class DrinkPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['drink' => 'kopi', 'ml' => 200, 'price' => 1],
            ['drink' => 'kopi', 'ml' => 300, 'price' => 1],
            ['drink' => 'kopi', 'ml' => 400, 'price' => 1],
            ['drink' => 'kopi', 'ml' => 500, 'price' => 1],
            ['drink' => 'teh', 'ml' => 200, 'price' => 1],
            ['drink' => 'teh', 'ml' => 300, 'price' => 1],
            ['drink' => 'teh', 'ml' => 400, 'price' => 1],
            ['drink' => 'teh', 'ml' => 500, 'price' => 1],
        ];

        foreach ($data as $row) {
            DrinkPrice::firstOrCreate($row);
        }
    }
}
