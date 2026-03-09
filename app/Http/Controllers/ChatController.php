<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    /**
     * Menerima pesan chat dari frontend, panggil Groq API langsung
     * via ChatService, dan kembalikan response sebagai SSE ke browser.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message'    => 'required|string|max:1000',
            'history'    => 'array',
            'history.*.role'    => 'required_with:history|string|in:user,assistant',
            'history.*.content' => 'required_with:history|string',
        ]);

        $chatService = new ChatService();

        $result = $chatService->chat(
            $request->input('message'),
            $request->input('history', [])
        );

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        // Kirim sebagai SSE word-by-word agar muncul efek typing seperti GPT
        return new StreamedResponse(function () use ($result) {
            $content = $result['content'];

            // Pecah response menjadi kata-kata (termasuk spasi & newline)
            $tokens = preg_split('/((?:\r?\n)+|\s+)/u', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            foreach ($tokens as $token) {
                echo "event: message\n";
                echo "data: " . json_encode(["content" => $token], JSON_UNESCAPED_UNICODE) . "\n\n";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // Delay antar kata untuk efek typing ala GPT (50ms)
                usleep(50000);
            }

            // Kirim event selesai
            echo "event: done\n";
            echo "data: " . json_encode(["content" => ""]) . "\n\n";
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection'    => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
