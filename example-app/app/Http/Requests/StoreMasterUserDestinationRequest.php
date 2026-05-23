<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMasterUserDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            "user_$id" => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!DB::table('customers')->where('name', $value)->exists()) {
                        $fail('利用者登録に登録されていない利用者です。');
                    }
                }
            ],

            "destination_$id" => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!DB::table('destinations')->where('destination', $value)->exists()) {
                        $fail('行き先登録に登録されていない行き先です。');
                    }
                }
            ],
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $id = $this->route('id');

        $errorCode = null;

        if ($validator->errors()->has("user_$id")) {
            $errorCode = 'user_not_found';
        } elseif ($validator->errors()->has("destination_$id")) {
            $errorCode = 'destination_not_found';
        }

        throw new HttpResponseException(
            redirect()
                ->route('master.page', [
                    'mode' => 'support',
                    'error' => $errorCode,
                ])
                ->withErrors($validator)
                ->withInput()
        );
    }




    public function messages(): array
    {
        $id = $this->route('id');

        return [
            "user_$id.required" => '利用者を入力してください',
            "destination_$id.required" => '行き先を入力してください',
        ];
    }
}