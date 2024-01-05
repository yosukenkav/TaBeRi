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
        // �o���f�[�V����
        $request->validate([
            'sentence' => 'required',
        ]);

        // ����
        $sentence = $request->input('sentence');

        // ChatGPT API����
        $chat_response = $this->chat_gpt("���{��ŉ������Ă�������", $sentence);

        return view('chat.gpt', compact('sentence', 'chat_response'));
    }
          /**
     * ChatGPT API�Ăяo��
     * Laravel HTTP
     */
    function chat_gpt($system, $user)
    {
        // ChatGPT API�̃G���h�|�C���gURL
        $url = "https://api.openai.com/v1/chat/completions";

        // API�L�[
        $api_key = env('OPENAI_API_KEY');

        // �w�b�_�[
        $headers = array(
            "Content-Type" => "application/json",
            "Authorization" => "Bearer $api_key"
        );

        // �p�����[�^
        $data = array(
            "model" => "gpt-4-vision-preview",
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => "How many grams of protein is in the dish in this image?"
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "https://msp.c.yimg.jp/images/v2/FUTi93tXq405grZVGgDqG3uPbEehoaE1x8k4iHtREk2CQrr27F9y77piL4TCEv75hNGMojgTSwVpq9eGVh36CkuA1wUr3ln1_o6Lb5ZQEhODdMiiDalnMTusagyBhGp0ynGaCXk64_MS7d9mKHg4t9dUoE0j4IzKgY7mkFbcs-XDQ86bZxJInosiZpfcuw2sm-IdQhe1k9mkFH97FntIaqgs7cXbBhuZzt9pldxTO9Sii2qcfaX5BkVVcdTaV0gXhAn5c4Pb7oIKJru4MkndC558lliU0iEBWsbBEYE4umIdXOKzXOau0Y7NWdN08Y4an3YRmvSR_F9vvX52zvfiAtB8OKqRR9lgQWo6zj3yEhkRN0bSuNJ5uAQBpCeec01SdsoIICmVu9WEcpEuanU8yA==/9cca7606cf5c15f4d2b0f737fa4503dc.jpg?errorImage=false"
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
            // �G���[
            return $response->json('error')['message'];
        }

        return $response->json('choices')[0]['message']['content'];
    }
}
