<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\DrinkPrice;
use App\Models\Transaction;

class PaymentController extends Controller
{
    private function pricing(string $drink): array {
        return DrinkPrice::where('drink', $drink)
            ->pluck('price', 'ml')
            ->toArray();
    }

    public function showDrinkPage() {
        return view('index');
    }

    public function showPayPage($drink) {
        $drink = strtolower($drink);
        return view('volume', [
            'pricing' => $this->pricing($drink),
            'drink' => $drink
        ]);
    }

    public function showDetail($drink, int $ml) {
        $drink = strtolower($drink);
        $pricing = $this->pricing($drink);
        abort_unless(array_key_exists($ml, $pricing), 404);

        $price   = $pricing[$ml];
        $estTime = (int) round($ml / 50);

        return view('detail', [
            'drink'   => strtolower($drink),
            'ml'      => $ml,
            'price'   => $price,
            'estTime' => $estTime,
        ]);
    }

    public function createTransaction(Request $request, $drink) {
        // Midtrans config
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $pricing = $this->pricing($drink);

        $ml = (int) $request->input('ml', 0);
        if (!array_key_exists($ml, $pricing)) {
            return response()->json(['error' => 'Pilihan volume tidak valid'], 422);
        }
        $amount = $pricing[$ml];
        $drink = strtolower($drink);

        $orderId = "WADAH-{$drink}-".now()->format('YmdHis')."-{$ml}-".random_int(1000,9999);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            // Tampilkan hanya QRIS di Snap
            'enabled_payments' => ['other_qris'],
            'item_details' => [[
                'id'       => "WADAH-{$drink}-{$ml}",
                'price'    => $amount,
                'quantity' => 1,
                'name'     => "Pengisian {$drink} {$ml}ml",
            ]],
            'customer_details' => [
                'first_name' => 'Pembeli',
                'email'      => 'user@example.com',
            ],
            'callbacks' => [
                'finish' => route('success'),
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }

    public function devicePoll(Request $request)
    {
        $drink = $request->query('drink', 'kopi');
        $file  = storage_path("app/device_job_{$drink}.json");

        if (!file_exists($file)) {
            return response()->json(['ok' => true, 'job' => null]);
        }

        $data = json_decode(file_get_contents($file), true);
        unlink($file); // hapus setelah dibaca agar hanya sekali dijalankan
        return response()->json(['ok' => true, 'job' => $data]);
    }

    public function showSuccess(Request $request) {
        $orderId = $request->query('order_id');
        $trx = Transaction::where('order_id', $orderId)->first();

        $ml      = (int) $request->query('ml');
        $price   = (int) $request->query('price');
        $estTime = (int) $request->query('estTime', 5);
        $tsRaw   = $request->query('ts');

        try {
            $ts = Carbon::parse($tsRaw);
        } catch (\Throwable $e) {
            $ts = now();
        }

        $ts = $ts->timezone(config('app.timezone', 'Asia/Jakarta'));

        return view('success', [
            // 'order_id' => $orderId,
            'ml'       => $ml,
            'price'    => $price,
            'estTime'  => $estTime,
            'ts'       => $ts,
            'issuer'   => $trx->issuer ?? null,
            'trx'      => $trx,
        ]);
    }

    // Webhook dari Midtrans
    public function handleNotification(Request $request) {
        $serverKey = config('midtrans.server_key');
    
        Log::info('Midtrans webhook payload', $request->all());
    
        $orderId     = (string) $request->input('order_id', '');
        $statusCode  = (string) $request->input('status_code', '');
        $grossAmount = (string) $request->input('gross_amount', '');
        $signature   = (string) $request->input('signature_key', '');
    
        // Validasi signature
        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        if (!hash_equals($expected, $signature)) {
            Log::warning('Midtrans signature mismatch', compact('orderId'));
            return response()->json(['message' => 'invalid signature'], 403);
        }
    
        $trxRaw  = $request->input('transaction_status');
        $fraud   = $request->input('fraud_status');

        $status = match ($trxRaw) {
            'capture', 'settlement' => 'success',
            'pending'               => 'pending',
            'deny', 'expire', 'cancel' => 'failed',
            default                 => 'failed',
        };

        //generate code transactions
        $code = $status === 'success'
        ? ('WADAH' . random_int(10000, 99999))
        : null;

        // Ambil issuer
        $issuer = $request->input('issuer')
                ?? $request->input('acquirer')
                ?? ($request->input('payment_type') === 'qris' ? null : null);

        // Ambil drink dan ml dari orderId
        $ml = 0; 
        $drink = 'kopi';
        if (preg_match('/WADAH-(kopi|teh)-\d{14}-(\d+)-\d{4}/i', $orderId, $m)) {
            $drink = strtolower($m[1]);
            $ml = (int) $m[2];
        }

        // Simpan ke DB
        Transaction::updateOrCreate(
            ['order_id' => $orderId],
            [
                'drink'   => $drink,
                'ml'      => $ml,
                'amount'  => (int) $grossAmount,
                'status'  => $status,
                'issuer'  => $issuer,
                'paid_at' => now(),
                'code_transactions' => $code ?? null,
            ]
        );

        // Jika success → buat job untuk ESP
        if ($status === 'success') {
            $job = [
                'ml'     => $ml,
                'drink'  => $drink,
                'at'     => now()->toIso8601String(),
            ];

            $file = storage_path("app/device_job_{$drink}.json");
            file_put_contents($file, json_encode($job));

            Log::info('✅ File job stored', compact('file','job'));
        }

        return response()->json(['message' => 'ok']);
    }
}
