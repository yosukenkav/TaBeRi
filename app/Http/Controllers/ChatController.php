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

        // // Path to your image
        // $imagePath = public_path('zQrve6h3X3aqe8dawj4lYfjbrxSkRQYHiSXNpkUY.jpg');

        // // Getting the base64 string
        // $base64Image = base64_encode($imagePath);
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
                        ["type" => "text", "text" => "Predict the protein content of the food in this image. Please answer with only numbers and do not include '~'."],
                        //Predict the protein content of the food in this image. Please answer with only numbers and do not include '~'.
                        // ['type' => 'image', 'image' => $base64Image],
                         [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => "https://msp.c.yimg.jp/images/v2/FUTi93tXq405grZVGgDqG3jb8zlkUnMmwCW3cMlg1cPjfEP_Y2tjCp4oct3h_8muoS-IBVASA-l7vYsxsVp18N93HqCzO4O-jVjrkrI3VyWWoufyTqqcDmb63QvdDis9H7-e5Kv_LNZfdfF3AsYD52l07nYXsqJu7to07QFUXTliqN2PYSGupXQjBOkFgmDNtyDbB9gY9p8FwEd_mtjVUPQkXC_TEaQY6nJSNPGvaoJq4ghxsC6HYl7CCQgPmCVsidoOTIiBdsbrSGLIwMJqtQ==/S20080225020002A_000.png?errorImage=false",
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
