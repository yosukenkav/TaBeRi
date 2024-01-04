<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chat.gpt');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function chat(Request $request)
    {
        // バリデーション
        $request->validate([
            'sentence' => 'required',
        ]);

        // 文章
        $sentence = $request->input('sentence');

        // ChatGPT API処理
        $chat_response = $this->chat_gpt("日本語で応答してください", $sentence);

        return view('chat.gpt', compact('sentence', 'chat_response'));
    }
          /**
     * ChatGPT API呼び出し
     * Laravel HTTP
     */
    function chat_gpt($system, $user)
    {
        // ChatGPT APIのエンドポイントURL
        $url = "https://api.openai.com/v1/chat/completions";

        // APIキー
        $api_key = env('OPENAI_API_KEY');

        // ヘッダー
        $headers = array(
            "Content-Type" => "application/json",
            "Authorization" => "Bearer $api_key"
        );

        // パラメータ
        $data = array(
            "model" => "gpt-4-vision-preview",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $system
                ],
                [
                    "role" => "user",
                    /*普通のChatGPTを使用するときはモデルを変え、この下のcontentのスラッシュを外し、
                    その下のcontentをメモ化する。その後gpt.blade.phpでチャット入力欄のスラッシュを外す*/
                    // "content" => $user
                     "content" => [
                        ["type" => "text", "text" => "この画像の食べ物のタンパク質量はいくらですか?数字だけで教えて。料理の説明は省いて、答えは一つだけで「～」は含めずに"],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => "https://msp.c.yimg.jp/images/v2/FUTi93tXq405grZVGgDqGx5cm8knTLo61O84kVTxOan841a30-aIJSoqkmlQNsP4-Qv0KVqX9M9vYFUiwJk7TWC4I9M2xzEn4jfvB8Tnx5W1RMwvdTKvg5pjf-M3lAKmHoFWxcKsmDfi9rrcY3k9Jl4FRESWO_vYjdXJqrqpz_sXjGAMkpj2eUUXlg3t3iiuCITFF-aPUGdyfrOra1WB6yFBs9oPqOusD6743oMlUc8EQYcyjwsGR8PM50WvMuj0oRxh8zvNXiOK1yLgoAmUiXs3b3kBjpdMWKXK42gzUtovefhytAgJXJFtHL6ebGFMmAg7ww3zxs_v9R7mTm4VyTmXGkbipENC5DHA6WE5LAbJ2-Olp65OWTjxpRNR-KMvjyQLznaq2deDq1ohPfhnLSFBs9oPqOusD6743oMlUc8EQYcyjwsGR8PM50WvMuj0oRxh8zvNXiOK1yLgoAmUiWg3I0Sdvq9AMtRSJ6QbCubY55o1Ttb_VoLhot389aS3HoFWxcKsmDfi9rrcY3k9Jl4FRESWO_vYjdXJqrqpz_sXjGAMkpj2eUUXlg3t3iiug2TUWon14TQrPwboFlQOaA==/260px-Shoyu_ramen2C_at_Kasukabe_Station_282014.05.0529_1.jpg?errorImage=false",
                            ],
                        ],
                    ],
                ]
            ]
        );

        $data['messages'] = mb_convert_encoding($data['messages'], 'UTF-8', 'UTF-8');
        $response = Http::withHeaders($headers)->post($url, $data);
        
        if ($response->json('error')) {
            // エラー
            return $response->json('error')['message'];
        }

        return $response->json('choices')[0]['message']['content'];
    }
}
