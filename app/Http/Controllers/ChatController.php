<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;

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
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "これは何ですか？"
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "https://msp.c.yimg.jp/images/v2/FUTi93tXq405grZVGgDqG9ue_Quma5tFs0mG052lIITpbnmW5ub2uxsMA3vVV7rBEk7Xgf2zSlOBszO421WRTW2l3tBIQ3GuhsxnRraQIJor28NtUl5FVN0XXNR-2n3Mv9qssVomL42ZYvsRDRUXFtXLR6zYpL2klkYTcvyW5jgtDhOH4zbQfXSJ_5CdwU4YQFK8fNTWtYXyDpJTJXQ0Aw==/6101-1311.JPG?errorImage=false"
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 300
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
