<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private function pricing(): array {
        return [
            200 => 1,
            300 => 700,
            500 => 1000,
            700 => 1300,
            1000 => 1500,
            1500 => 2000,
        ];
    }

    public function showPayPage() {
        return view('pay', ['pricing' => $this->pricing()]);
    }

    public function showDetail(int $ml) {
        $pricing = $this->pricing();
        abort_unless(array_key_exists($ml, $pricing), 404);

        $price   = $pricing[$ml];
        $estTime = max(4, (int) round($ml / 50));

        return view('detail', [
            'ml'      => $ml,
            'price'   => $price,
            'estTime' => $estTime,
        ]);
    }

    public function createTransaction(Request $request) {
        // Midtrans config
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $pricing = $this->pricing();

        $ml = (int) $request->input('ml', 0);
        if (!array_key_exists($ml, $pricing)) {
            return response()->json(['error' => 'Pilihan volume tidak valid'], 422);
        }
        $amount = $pricing[$ml];

        $orderId = 'Hydro-'.now()->format('YmdHis').'-'.random_int(1000,9999);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            // Tampilkan hanya QRIS di Snap
            'enabled_payments' => ['other_qris'],
            'item_details' => [[
                'id'       => 'HYDRO-'.$ml,
                'price'    => $amount,
                'quantity' => 1,
                'name'     => "Pengisian Air {$ml}ml",
            ]],
            'customer_details' => [
                'first_name' => 'Pembeli',
                'email'      => 'user@example.com',
            ],
            'callbacks' => [
                'finish' => config('app.url').'/?finish=1',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }

    public function showSuccess(Request $request) {
        $orderId = $request->query('order_id');
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
            'order_id' => $orderId,
            'ml'       => $ml,
            'price'    => $price,
            'estTime'  => $estTime,
            'ts'       => $ts,
        ]);
    }

    public function devicePoll(Request $request) {
        $token = (string) $request->query('token','');
        if ($token === '' || $token !== 'RELAY123') {
            return response()->json(['ok'=>false,'error'=>'bad token'], 403);
        }
    
        // Ambil & hapus tugas atomically
        $key = "device_job_{$token}";
        $job = Cache::pull($key); // null kalau tidak ada
    
        if (!$job) {
            return response()->json(['ok'=>true,'job'=>null]); // tidak ada tugas
        }
        return response()->json(['ok'=>true,'job'=>$job]); // contoh: ['ms'=>5000]
    }

    // Webhook dari Midtrans
    public function handleNotification(Request $request) {
        $serverKey = config('midtrans.server_key');
    
        Log::info('Midtrans webhook payload', $request->all());
    
        $orderId     = (string) $request->input('order_id', '');
        $statusCode  = (string) $request->input('status_code', '');
        $grossAmount = (string) $request->input('gross_amount', '');
        $signature   = (string) $request->input('signature_key', '');
        $trxStatus = (string) $request->input('transaction_status', '');
        $fraud     = $request->input('fraud_status');
    
        // Validasi signature
        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        if (!hash_equals($expected, $signature)) {
            Log::warning('Midtrans signature mismatch', compact('orderId','statusCode','grossAmount'));
            return response()->json(['message' => 'invalid signature'], 403);
        }
    
        Log::info('Midtrans parsed status', [
            'orderId' => $orderId,
            'transaction_status' => $trxStatus,
            'fraud_status'       => $fraud
        ]);
    
        // eksekusi pada settlement/capture
        if (in_array($trxStatus, ['capture','settlement'], true) && ($fraud === 'accept' || $fraud === null)) {
            $cacheKey = 'midtrans_done_'.$orderId;
            if (!Cache::add($cacheKey, true, now()->addHours(24))) {
                Log::info('Skip duplicate execution', compact('orderId','trxStatus'));
                return response()->json(['message' => 'ok (duplicate)']);
            }

            $deviceToken = 'RELAY123';
            $jobKey      = "device_job_{$deviceToken}";
            $jobPayload  = ['ms' => 5000, 'at' => now()->toIso8601String(), 'order_id'=>$orderId];

            Cache::put($jobKey, $jobPayload, now()->addMinutes(2));
            Log::info('Enqueued device job', ['key'=>$jobKey,'payload'=>$jobPayload]);
        } else {
            Log::info('Payment not settled yet, skip', compact('orderId','trxStatus','fraud'));
        }        
    
        return response()->json(['message' => 'ok']);
    }
 
}