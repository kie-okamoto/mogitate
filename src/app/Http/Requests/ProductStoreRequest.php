<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => [], // ここでは何も書かない（すべて withValidator で処理）
            'image' => ['required', 'image', 'mimes:png,jpeg'],
            'seasons' => 'required|array',
            'description' => ['required', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'image.required' => '商品画像を登録してください',
            'image.image' => '画像ファイルを指定してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'seasons.required' => '季節を選択してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            \Log::debug('price', ['value' => $this->input('price')]);

            $price = $this->input('price');

            // 必須チェック
            if ($price === null || $price === '') {
                $validator->errors()->add('price', '値段を入力してください');
            }

            // 数値チェック
            if (!is_numeric($price)) {
                $validator->errors()->add('price', '数値で入力してください');
            }

            // 範囲チェック
            if (is_numeric($price) && ($price < 0 || $price > 10000)) {
                $validator->errors()->add('price', '0~10000円以内で入力してください');
            }
        });
    }
}
