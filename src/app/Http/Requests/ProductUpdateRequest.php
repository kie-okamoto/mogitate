<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|between:0,10000',
            'description' => 'required|string|max:120',
            'seasons'     => 'required|array|min:1',
            'image'       => 'required|image|mimes:jpeg,png,jpg',
        ];
    }

    public function messages(): array
    {
        return [
            // 商品名
            'name.required' => '商品名を入力してください',

            // 値段
            'price.required' => '値段を入力してください',
            'price.numeric'  => '数値で入力してください',
            'price.between'  => '0~10000円以内で入力してください',

            // 商品画像
            'image.required' => '商品画像を登録してください',
            'image.image'    => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes'    => '「.png」または「.jpeg」形式でアップロードしてください',

            // 季節
            'seasons.required' => '季節を選択してください',

            // 商品説明
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',
        ];
    }
}
