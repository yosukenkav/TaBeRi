<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Dish;

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
            'protein_drinks' => 'required|integer|min:0|max:10',
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
}
