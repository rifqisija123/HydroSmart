<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    public function showPayPage() {
        return view('pay');
    }

    public function createTransaction(Request $request) {
        // Midtrans config
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $orderId = 'LED-'.now()->format('YmdHis').'-'.random_int(1000,9999);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => 1,
            ],
            // Tampilkan hanya QRIS di Snap
            'enabled_payments' => ['other_qris'],
            'customer_details' => [
                'first_name' => 'LED Control',
                'email'      => 'user@example.com',
            ],
            'callbacks' => [
                'finish' => config('app.url').'/?finish=1',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
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
            Log::warning('Midtrans signature mismatch', compact('orderId','statusCode','grossAmount'));
            return response()->json(['message' => 'invalid signature'], 403);
        }
    
        $trxStatus = (string) $request->input('transaction_status', '');
        $fraud     = $request->input('fraud_status');
    
        Log::info('Midtrans parsed status', [
            'orderId' => $orderId,
            'transaction_status' => $trxStatus,
            'fraud_status'       => $fraud
        ]);
    
        // eksekusi pada settlement/capture
        $okStatuses = ['capture','settlement'];
        if (in_array($trxStatus, ['capture','settlement'], true) && ($fraud === 'accept' || $fraud === null)) {
            $cacheKey = 'midtrans_done_'.$orderId;
            if (!Cache::add($cacheKey, true, now()->addHours(24))) {
                Log::info('Skip duplicate execution', compact('orderId','trxStatus'));
                return response()->json(['message' => 'ok (duplicate)']);
            }

            $deviceUrl = config('midtrans.device_url');
            try {
                $resp = Http::timeout(8)->retry(2, 200)->get($deviceUrl);
                Log::info('Device trigger response', [
                    'url'    => $deviceUrl,
                    'status' => $resp->status(),
                    'body'   => $resp->body(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Device trigger failed', ['url'=>$deviceUrl,'error'=>$e->getMessage()]);
            }
        } else {
            Log::info('Payment not settled yet, skip', ['trxStatus'=>$trxStatus,'fraud'=>$fraud]);
        }        
    
        return response()->json(['message' => 'ok']);
    }
    
}
