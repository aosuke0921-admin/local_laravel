<?php //98点

// StoreRideRequest.php → StorePostRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;

//class StoreRideRequest extends FormRequest
class StorePostRequest extends FormRequest
{
    /**
     * 認可
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
public function rules(): array
{
    return [
        'user.*' => 'nullable|string',

        'departureTime.*' => 'required_with:user.*|date_format:H:i',
        'arrivalTime.*' => 'required_with:user.*|date_format:H:i',

        'destination.*' => 'required_with:user.*|filled',
    ];
}

    /**
     * エラーメッセージ（任意）
     */
    public function messages(): array
    {
        return [
            'departureTime.*.required_with' => '発時刻を入力してください',
            'departureTime.*.date_format' => '発時刻は HH:MM 形式で入力してください',
            'arrivalTime.*.required_with' => '着時刻を入力してください',
            'arrivalTime.*.date_format' => '着時刻は HH:MM 形式で入力してください',

        // 追加ここ
        'destination.*.required_with' => '行き先を選択してください',
        'destination.*.string' => '行き先の形式が不正です',
        ];
    }

    // ←ここに書く
    protected function failedValidation(Validator $validator)
    {
        //dd('バリデーションエラー', $validator->errors()->all());
    }

}