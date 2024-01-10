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
        // return view('dish.index', compact('sentence', 'chat_response'));
        // return redirect()->route('dish.index');
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

        // Path to your image
        //���Ƃ��Ƃ�$imagePath = public_path('image_path');�ł��ꂾ�ƁATaBeRi/public(���J�f�B���N�g��)�ɉ摜�������ƃG���[�ɂȂ�B
        //storage_path('app/public/' �Ƃ��邱�ƂŁAstorage/app/public�̃t�@�C���̐�΃p�X���擾�ł���B
        $imagePath = storage_path('app/public/'.'BiUh2HfSEjZ13dCH8ubQfYGbXLIBYS8SParhl3TZ.jpg');

        // Getting the base64 string
        $base64Image = base64_encode(file_get_contents($imagePath));
        // ddd($base64Image);

        // �w�b�_�[
        $headers = array(
            "Content-Type" => "application/json",
            "Authorization" => "Bearer $api_key"
        );

        // �p�����[�^
        $data = array(
            "model" => "gpt-4-vision-preview",
            "max_tokens" => 300,
            "messages" => [
                [
                    "role" => "system",
                    "content" => $system
                ],
                [
                    "role" => "user",
                    /*���ʂ�ChatGPT���g�p����Ƃ��̓��f����ς��A���̉���content�̃X���b�V�����O���A
                    ���̉���content������������B���̌�gpt.blade.php�Ń`���b�g���͗��̃X���b�V�����O��*/
                    // "content" => $user
                    "content" => [
                        ["type" => "text", "text" => "What is in the image?"],
                        //Predict the protein content of the food in this image. Please answer with only numbers and do not include '~'.
                        // ['type' => 'image', 'image' => $base64Image],
                         [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => "data:image/jpeg;base64,{$base64Image}",
                            ],
                        ],
                    ],
                ]
            ]
             
        );
        
        

        $data['messages'] = mb_convert_encoding($data['messages'], 'UTF-8', 'UTF-8');
        $response = Http::withHeaders($headers)->post($url, $data);
        
        if ($response->json('error')) {
            // �G���[
            return $response->json('error')['message'];
        }

        return $response->json('choices')[0]['message']['content'];
    }
    //  public function encodeImage($imagePath)
    // {
    //     $imageData = base64_encode($imagePath);
    //     return  $imageData;
    // }
}
