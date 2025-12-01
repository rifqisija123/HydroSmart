<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/transactions.sql');
        $sql = file_get_contents($path);

        preg_match_all('/INSERT INTO `transactions` .*?VALUES\s*(.*?);/is', $sql, $matches);

        if (!isset($matches[1][0])) {
            dump("No SQL data found!");
            return;
        }

        $rowsRaw = explode("),", trim($matches[1][0], " ,;"));
        
        foreach ($rowsRaw as $row) {
            $clean = trim($row, " ()");
            $values = str_getcsv($clean, ',', "'");
            
            if (count($values) < 10) continue;

            [$id, $order_id, $drink, $ml, $amount, $status, $issuer, $paid_at, $created_at, $updated_at] = $values;

            $code = $status === 'success'
                ? ('WADAH' . random_int(10000, 99999))
                : null;

            Transaction::create([
                'order_id'          => $order_id,
                'code_transactions' => $code,
                'drink'             => $drink,
                'ml'                => (int)$ml,
                'amount'            => (int)$amount,
                'status'            => $status,
                'issuer'            => $issuer ?: null,
                'paid_at'           => $paid_at,
                'created_at'        => $created_at,
                'updated_at'        => $updated_at,
            ]);
        }
    }
}
