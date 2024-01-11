<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Dish;
use Illuminate\Support\Facades\Http;

class DishController extends Controller
{
    private $path1 = null;
    private $path2 = null;
    private $path3 = null;
    private $pathNumber = 0;
    private $protein_breakfast = null;
    private $protein_lunch = null;
    private $protein_dinner = null;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $dishes = Dish::getAllOrderByUpdated_at();
         return response()->view('dish.index',compact('dishes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('dish.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                // バリデーション
        $validator = Validator::make($request->all(), [
            'weight' => 'required | numeric|min:0',
            'protein_drinks' => 'required|integer|min:0|max:20',
            'image_breakfast' => ['image', 'mimes:jpeg,png,jpg,gif'],
            'image_lunch' => ['image', 'mimes:jpeg,png,jpg,gif'],
            'image_dinner' => ['image', 'mimes:jpeg,png,jpg,gif'],


        ]);
        // バリデーションエラー
        if ($validator->fails()) {
            return redirect()
            ->route('dish.create')
            ->withInput()
            ->withErrors($validator);
        }
        // 体重を1.4倍した理想的タンパク質量を計算
        $idealProteinAmount = $request->input('weight') * 1.4;

        // プロテイン1杯につき１０gとしてタンパク質量を計算
        $actualProteinAmount = $request->input('protein_drinks') * 10;
        
        $image = $request->file('image_breakfast');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_breakfast')) {
            $path = \Storage::put('/public', $image);
            $this->path1 = explode('/', $path)[1];
            //$pathNumberに画像のパス今回なら$path1の1を代入こうすることでchat_gpt_2メソッドで$path1~$path3を見分けられるようにしている。
            $this ->pathNumber = 1;
            // chat_2 メソッドを呼び出す
            $this->protein_breakfast = $this-> chat_2($request); 
        }
        
        $image = $request->file('image_lunch');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_lunch')) {
            $path = \Storage::put('/public', $image);
            $this->path2 = explode('/', $path)[1];
            $this ->pathNumber = 2;
            $this->protein_lunch = $this-> chat_2($request);
        }

        $image = $request->file('image_dinner');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_dinner')) {
            $path = \Storage::put('/public', $image);
            $this->path3 = explode('/', $path)[1];
            $this ->pathNumber = 3;
            $this->protein_dinner = $this-> chat_2($request);
        }
        
        //その日に摂取したタンパク質量の計算
        $actualProteinAmountD = $request->input('protein_drinks') * 10 + ($this -> protein_breakfast) + ($this -> protein_lunch) + ($this -> protein_dinner);

        //理想のタンパク質量と実際の摂取量との比較
        $proteinAmountJudge = NULL;//初期化

        if($idealProteinAmount > $actualProteinAmount or $idealProteinAmount > $actualProteinAmountD ){
            $proteinAmountJudge ='少ない';
        }
        elseif($idealProteinAmount == $actualProteinAmount or $idealProteinAmount == $actualProteinAmountD){
            $proteinAmountJudge ='ちょうど良い';
        }
        elseif($idealProteinAmount < $actualProteinAmount or $idealProteinAmount < $actualProteinAmountD){
            $proteinAmountJudge ='多い';
        }
         

        $dish = $request->all();
        //$user = Auth::user()->id;        // ログインユーザーのIDを取得
        //$dish['user_id'] = $user;
        $dish['image_breakfast'] = $this->path1;
        $dish['image_lunch'] = $this->path2;
        $dish['image_dinner'] = $this->path3;  //$path[0] はディレクトリ、$path[1] はファイル名
        //Dish::create($dish);


        
        $result = Dish::create([
            'weight' => $request->input('weight'),
            'protein_drinks' => $request->input('protein_drinks'),
            'ideal_protein_amount' => $idealProteinAmount, 
            'actual_protein_amount' => $actualProteinAmount,
            'actual_protein_amount_d' => $actualProteinAmountD,
            'image_breakfast' => $this->path1,
            'image_lunch' => $this->path2,
            'image_dinner' => $this->path3,
            'protein_amount_judge' => $proteinAmountJudge,
            // 'answer' => $answer,
            'protein_breakfast' => $this->protein_breakfast,
            'protein_lunch' => $this->protein_lunch,
            'protein_dinner' => $this->protein_dinner,
        ]);
       
        // リダイレクト
        return redirect()->route('dish.index');
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
        $result = Dish::find($id)->delete();
        return redirect()->route('dish.index');
    }
    public function chat_2(Request $request)
    {
        //バリデーション
        // $request->validate([
        //     'sentence' => 'required',
        // ]);

        // 文章
        $sentence = ('ラーメンについて教えて');

        // ChatGPT API処理
        $chat_response = $this->chat_gpt_2("日本語で応答してください",$sentence);
        // ("日本語で応答してください", $sentence);

        // return view('dish.index', compact('sentence', 'chat_response'));
        return $chat_response;
        // return redirect()->route('dish.index',compact('chat_response'));
    }
          /**
     * ChatGPT API呼び出し
     * Laravel HTTP
     */
    function chat_gpt_2($system, $user)
    {
        // ChatGPT APIのエンドポイントURL
        $url = "https://api.openai.com/v1/chat/completions";

        // APIキー
        $api_key = env('OPENAI_API_KEY');

        // $path_breakfast = session('path_breakfast');

        // Path to your image
        //もともとは$imagePath = public_path('image_path');でこれだと、TaBeRi/public(公開ディレクトリ)に画像が無いとエラーになる。
        //storage_path('app/public/' とすることで、storage/app/publicのファイルの絶対パスが取得できる。
        //$this->{ "path{$this->pathNumber}"}のところは朝昼晩のそれぞれ違う画像のパスを読み込むために$pathNumberを定義し画像が登録される時点で数字を$pathNumberに代入させている
        //↑これを実現する事で関数を定義する場所（関数の内か外か）や呼び出し方（$this）を学んだ。
        $imagePath = storage_path('app/public/'. $this->{ "path{$this->pathNumber}"});

        // Getting the base64 string
        $base64Image = base64_encode(file_get_contents($imagePath));

        // ヘッダー
        $headers = array(
            "Content-Type" => "application/json",
            "Authorization" => "Bearer $api_key"
        );

        // パラメータ
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
                    /*普通のChatGPTを使用するときはモデルを変え、この下のcontentのスラッシュを外し、
                    その下のcontentをメモ化する。その後gpt.blade.phpでチャット入力欄のスラッシュを外す*/
                    // "content" => $user
                    "content" => [
                        ["type" => "text", "text" => "Predict the protein content of the food in this image. Please answer with only numbers and do not include '~'."],
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
            // エラー
            return $response->json('error')['message'];
        }

        return $response->json('choices')[0]['message']['content'];
    }

}
