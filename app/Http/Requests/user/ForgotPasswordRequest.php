<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest{
    /*
     * Check các điều kiện khác, return false để từ chối
     */
    public function authorize()
    {
        return true;
    }

    /*
     * Kiểm tra dữ liệu
     */
    public function rules(){
        return [
            'email'        => [
                'required',
                'regex:/^.+@.+$/i',
                'exists:users,email',
                'max:50'
            ]
        ];
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required'          => __('user/register.invalid_required'),
            'email.exists'        => __('user/forgot-password.invalid_email_no_exists'),
            '*.*'                 => __('user/register.data_is_incorrect'),
        ];
    }

    /*
     * Trả về dữ liệu JSON
     */
    protected function failedValidation(Validator $validator){
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], 200)
        );
    }
}