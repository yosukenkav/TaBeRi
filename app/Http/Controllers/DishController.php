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
                // �o���f�[�V����
        $validator = Validator::make($request->all(), [
            'weight' => 'required | numeric|min:0',
            'protein_drinks' => 'required|integer|min:0|max:10',
          
        ]);
        // �o���f�[�V����:�G���[
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

        // create()�͍ŏ�����p�ӂ���Ă���֐�
        // �߂�l�͑}�����ꂽ���R�[�h�̏��
        $result = Dish::create([
            'weight' => $request->input('weight'),
            'protein_drinks' => $request->input('protein_drinks'),
            'ideal_protein_amount' => $idealProteinAmount, 
            'actual_protein_amount' => $actualProteinAmount,
        ]); 
        // ���[�e�B���O�utodo.index�v�Ƀ��N�G�X�g���M�i�ꗗ�y�[�W�Ɉړ��j
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
        //
    }
}
