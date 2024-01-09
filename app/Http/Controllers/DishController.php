<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Dish;
use Illuminate\Support\Facades\Http;

class DishController extends Controller
{
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

        //理想のタンパク質量と実際の摂取量との比較
        $proteinAmountJudge = NULL;//初期化

        if($idealProteinAmount > $actualProteinAmount){
            $proteinAmountJudge ='少ない';
        }
        elseif($idealProteinAmount == $actualProteinAmount){
            $proteinAmountJudge ='ちょうど良い';
        }
        elseif($idealProteinAmount < $actualProteinAmount){
            $proteinAmountJudge ='多い';
        }
        
        $image = $request->file('image_breakfast');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_breakfast')) {
            $path1 = \Storage::put('/public', $image);
            $path1 = explode('/', $path1);
        }
        
        $image = $request->file('image_lunch');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_lunch')) {
            $path2 = \Storage::put('/public', $image);
            $path2 = explode('/', $path2);
        }
        $image = $request->file('image_dinner');
        // 画像がアップロードされていれば、storageに保存
        if ($request->hasFile('image_dinner')) {
            $path3 = \Storage::put('/public', $image);
            $path3 = explode('/', $path3);
        }
        
        $dish = $request->all();
        //$user = Auth::user()->id;        // ログインユーザーのIDを取得
        //$dish['user_id'] = $user;
        $dish['image_breakfast'] = $path1[1];
        $dish['image_lunch'] = $path2[1];
        $dish['image_dinner'] = $path3[1];  //$path[0] はディレクトリ、$path[1] はファイル名
        //Dish::create($dish);


        // create()�͍ŏ�����p�ӂ���Ă���֐�
        // �߂�l�͑}�����ꂽ���R�[�h�̏��
        $result = Dish::create([
            'weight' => $request->input('weight'),
            'protein_drinks' => $request->input('protein_drinks'),
            'ideal_protein_amount' => $idealProteinAmount, 
            'actual_protein_amount' => $actualProteinAmount,
            'image_breakfast' => $path1[1],
            'image_lunch' => $path2[1],
            'image_dinner' => $path3[1],
            'protein_amount_judge' => $proteinAmountJudge
        ]);
        // chat_2 メソッドを呼び出す
        $this-> chat_2($request);
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
        // バリデーション
        $request->validate([
            'sentence' => 'required',
        ]);

        // 文章
        $sentence = $request->input('sentence');

        // ChatGPT API処理
        $chat_response = $this->chat_gpt_2("日本語で応答してください", $sentence);

        return view('dish.index', compact('sentence', 'chat_response'));
        // return compact('chat_response');
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
                    "content" => $user
                    // "content" => [
                    //     ["type" => "text", "text" => "この画像には何が映っていますか？"],
                    //     [
                    //         "type" => "image_url",
                    //         "image_url" => [
                    //             "url" => "https://msp.c.yimg.jp/images/v2/FUTi93tXq405grZVGgDqGx5cm8knTLo61O84kVTxOan841a30-aIJSoqkmlQNsP4-Qv0KVqX9M9vYFUiwJk7TWC4I9M2xzEn4jfvB8Tnx5W1RMwvdTKvg5pjf-M3lAKmHoFWxcKsmDfi9rrcY3k9Jl4FRESWO_vYjdXJqrqpz_sXjGAMkpj2eUUXlg3t3iiuCITFF-aPUGdyfrOra1WB6yFBs9oPqOusD6743oMlUc8EQYcyjwsGR8PM50WvMuj0oRxh8zvNXiOK1yLgoAmUiXs3b3kBjpdMWKXK42gzUtovefhytAgJXJFtHL6ebGFMmAg7ww3zxs_v9R7mTm4VyTmXGkbipENC5DHA6WE5LAbJ2-Olp65OWTjxpRNR-KMvjyQLznaq2deDq1ohPfhnLSFBs9oPqOusD6743oMlUc8EQYcyjwsGR8PM50WvMuj0oRxh8zvNXiOK1yLgoAmUiWg3I0Sdvq9AMtRSJ6QbCubY55o1Ttb_VoLhot389aS3HoFWxcKsmDfi9rrcY3k9Jl4FRESWO_vYjdXJqrqpz_sXjGAMkpj2eUUXlg3t3iiug2TUWon14TQrPwboFlQOaA==/260px-Shoyu_ramen2C_at_Kasukabe_Station_282014.05.0529_1.jpg?errorImage=false",
                    //         ],
                    //     ],
                    // ],
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
