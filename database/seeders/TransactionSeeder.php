<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drinks = ['kopi', 'teh'];
        foreach (range(1, 30) as $i) {
            $drink = $drinks[array_rand($drinks)];
            $ml = [200, 300, 400, 500, 700][array_rand([200, 300, 400, 500, 700])];
            $amount = $ml * rand(5, 8); // misal harga per 1ml = 5–8
            $date = Carbon::now()->subDays(rand(0, 30));

            Transaction::create([
                'order_id' => "WADAH-{$drink}-{$date->format('Ymd')}-{$ml}-" . rand(1000,9999),
                'drink' => $drink,
                'ml' => $ml,
                'amount' => $amount,
                'status' => 'success',
                'paid_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
