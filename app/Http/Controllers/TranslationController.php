<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class TranslationController extends Controller
{
    // Hiển thị trang dịch SRT
    public function showTranslatePage()
    {
        return view('translate');
    }

    public function translate(Request $request)
    {

        // Kiểm tra method
        if ($request->method() !== 'POST') {
            return response()->json(['error' => 'Only POST requests allowed'], 405);
        }

        $inputContent = $request->input('inputContent');
        $apiKey = $request->input('apiKey');

        if (!$inputContent || !$apiKey) {
            return response()->json(['error' => 'Input content and API Key are required.'], 400);
        }

        $prompt = "
        Translate the subtitles in this file into Vietnamese with the following requirements:
        - Maintain the original format, including sequence numbers, timestamps, and the number of lines.
        - The translations must match the context, culture, and situations occurring in the movie.
        - Preserve the capitalization exactly as in the original text.
        - Do not merge content from different timestamps into a single translation block.
        - Return only the translated content in the specified format, without any additional explanations, introductions, or questions.
        " . $inputContent;

        try {
            // Gửi yêu cầu đến API của Google Gemini
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => $prompt]],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 50,
                    'topP' => 0.9,
                    'maxOutputTokens' => 8192,
                    'responseMimeType' => 'text/plain',
                ],
            ]);

            $responseData = $response->json();

            if (isset($responseData['candidates']) && count($responseData['candidates']) > 0) {
                return response()->json([
                    'translatedContent' => $responseData['candidates'][0]['content']['parts'][0]['text'],
                ]);
            }

            return response()->json(['error' => 'Translation failed.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error.'], 500);
        }
    }
}
