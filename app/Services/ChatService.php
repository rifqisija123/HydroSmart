<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    protected string $model = 'llama-3.3-70b-versatile';
    protected int $maxHistory = 15;

    /**
     * Ambil data minuman dari tabel drink_prices.
     */
    protected function getDrinkPrices(): array
    {
        try {
            return DB::table('drink_prices')
                ->select('drink', 'ml', 'price')
                ->orderBy('drink')
                ->orderBy('ml')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::warning('[ChatService] DB error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format data minuman jadi teks.
     */
    protected function buildDrinkInfo(): string
    {
        $rows = $this->getDrinkPrices();
        if (empty($rows)) {
            return 'Data minuman belum tersedia di database.';
        }

        // Group by drink
        $drinks = [];
        foreach ($rows as $row) {
            $name = ucfirst($row->drink);
            if (!isset($drinks[$name])) {
                $drinks[$name] = [];
            }
            $drinks[$name][] = $row;
        }

        $lines = [];
        foreach ($drinks as $drinkName => $items) {
            $lines[] = "\n**{$drinkName}:**";
            foreach ($items as $item) {
                $price = number_format($item->price, 0, ',', '.');
                $lines[] = "  - {$item->ml} ml â†’ Rp {$price}";
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Build system prompt lengkap (sama seperti context.py).
     */
    public function getSystemPrompt(): string
    {
        $drinkInfo = $this->buildDrinkInfo();

        return <<<PROMPT
Kamu adalah **asisten chatbot WADAH** (Water Automation Dispenser Aman Hemat).

## Tentang WADAH
WADAH adalah aplikasi otomatisasi pengisian minuman berbasis IoT. Pengguna bisa memilih minuman, memilih volume, lalu membayar via QRIS (Midtrans). Setelah pembayaran berhasil, dispenser akan otomatis mengisi minuman ke wadah pengguna.

## Cara Penggunaan
1. Buka aplikasi WADAH di link "https://wadah.smkn9kotabekasi.sch.id"
2. Pilih jenis minuman (contoh: Kopi, Teh Tarik)
3. Pilih volume yang diinginkan (contoh: 200ml, 300ml, dll)
4. Lihat detail harga dan estimasi waktu pengisian
5. Klik "Lanjutkan Pengisian" untuk membayar via QRIS
6. Scan QRIS dengan aplikasi pembayaran (GoPay, OVO, DANA, dll.)
7. Setelah pembayaran berhasil, dispenser akan otomatis mengisi minuman

## Daftar Minuman & Harga
{$drinkInfo}

## Informasi Tambahan
- Pembayaran menggunakan QRIS melalui Midtrans
- Estimasi waktu pengisian: sekitar 1 detik per 50ml
- WADAH dirancang dan dibuat oleh Tim Inovasi Teknologi Metatech dari Jurusan SIJA (Sistem Informasi Jaringan dan Aplikasi) SMKN 9 Kota Bekasi
- Lokasi WADAH berada di SMKN 9 Kota Bekasi, Jl. Cikunir Raya Jl. H. Abas, RT.001/RW.002, Jakamulya, Kec. Bekasi Sel., Kota Bks, Jawa Barat 17146. Siapa pun bisa datang untuk mencoba WADAH secara langsung.

## Komponen Hardware (Alat-Alat WADAH)
- **ESP8266 (NodeMCU)**: Mikrokontroler berbasis Wi-Fi yang menjadi otak utama sistem WADAH. Berfungsi untuk menghubungkan seluruh komponen hardware ke internet, menerima perintah dari server (seperti perintah mulai mengisi minuman setelah pembayaran berhasil), serta mengirim data sensor kembali ke server.
- **Power Supply 12V**: Sumber daya listrik utama yang menyuplai tegangan ke seluruh komponen hardware WADAH, terutama pompa dan relay. Mengubah tegangan AC dari stop kontak menjadi DC 12V yang aman untuk komponen elektronik.
- **Relay 2 Channel**: Modul saklar elektronik yang dikendalikan oleh ESP8266. Berfungsi sebagai penghubung dan pemutus arus listrik ke pompa. Dengan 2 channel, relay dapat mengontrol dua pompa secara independen untuk masing-masing jenis minuman (Kopi dan Teh Tarik).
- **Pompa 12V (Water Pump)**: Pompa air DC 12V yang bertugas memompa minuman dari wadah penyimpanan ke wadah/gelas pengguna. Pompa ini diaktifkan oleh relay setelah menerima sinyal dari ESP8266 bahwa pembayaran berhasil.
- **Water Flow Sensor**: Sensor aliran air yang mengukur volume cairan yang melewatinya. Berfungsi untuk menghitung secara akurat berapa mililiter (ml) minuman yang sudah dikeluarkan, sehingga volume yang diterima pengguna sesuai dengan yang dipesan dan dibayar.
- **Kabel Jumper**: Kabel penghubung antar komponen elektronik (ESP8266, relay, sensor, pompa). Digunakan untuk menyambungkan sinyal data dan arus listrik antar modul agar seluruh sistem dapat bekerja sebagai satu kesatuan.

## Instruksi
- Selalu jawab dalam **Bahasa Indonesia**
- Jawab pertanyaan secara singkat, jelas, dan ramah
- Jika ini adalah pesan pertama dari user (tidak ada history sebelumnya), awali jawabanmu dengan sapaan hangat seperti: "Selamat datang di WADAH Bot! ðŸ‘‹ Saya asisten virtual WADAH yang siap membantu Anda." lalu jawab pertanyaan mereka
- Jika ditanya hal di luar konteks WADAH, jawab dengan sopan bahwa kamu hanya bisa membantu seputar aplikasi WADAH
- Gunakan emoji secukupnya untuk membuat percakapan lebih hidup
- Jangan berikan informasi harga yang tidak ada di daftar di atas
PROMPT;
    }

    /**
     * Kirim pesan ke Groq API dan dapatkan jawaban lengkap.
     */
    public function chat(string $message, array $history = []): array
    {
        $apiKey = env('GROQ_API_KEY');
        if (!$apiKey) {
            return ['error' => 'GROQ_API_KEY belum diset di .env'];
        }

        // Build messages array
        $messages = [
            ['role' => 'system', 'content' => $this->getSystemPrompt()],
        ];

        // Ambil N pesan terakhir dari history
        $recentHistory = array_slice($history, -$this->maxHistory);
        foreach ($recentHistory as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        // Tambah pesan baru dari user
        $messages[] = ['role' => 'user', 'content' => $message];

        // Call Groq API via cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode([
                'model'       => $this->model,
                'messages'    => $messages,
                'temperature' => 0.7,
                'max_tokens'  => 1024,
                'stream'      => false,
            ]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('[ChatService] cURL error: ' . $error);
            return ['error' => 'Connection error: ' . $error];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200) {
            $errorMsg = $data['error']['message'] ?? 'Groq API error (HTTP ' . $httpCode . ')';
            Log::error('[ChatService] Groq API error: ' . $errorMsg);
            return ['error' => $errorMsg];
        }

        $content = $data['choices'][0]['message']['content'] ?? '';
        return ['content' => $content];
    }
}
