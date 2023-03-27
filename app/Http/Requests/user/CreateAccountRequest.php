<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreateAccountRequest extends FormRequest{
    /*
     * Check các điều kiện khác, return false để từ chối
     */
    public function authorize(){
        return true;
    }

    /*
     * Kiểm tra dữ liệu
     */
    public function rules(){
        return [
            'name'    => [
                'required',
                'min:2',
                'max:30'
            ],
            'phone_number' => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:9',
                'unique:users,phone_number'
            ],
            'email'        => [
                'required',
                'regex:/^.+@.+$/i',
                'unique:users,email',
                'max:50'
            ],
            'password'     => [
                'required',
                'min:6',
                'confirmed'
            ],
            'captcha' => function($attribute, $value, $fail) {
                if( strlen( session('user_register_data')['name'] ?? null) > 0){
                    return; // Không cần nhập captcha nếu đăng ký qua GG,FB
                }
                if( empty($value) || $value != session('captcha_code') ){
                    return $fail( __('user/register.invalid_captcha_is_incorrect') );
                }
            }
        ];
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required'          => __('user/register.invalid_required'),
            'password.min'        => __('user/register.invalid_password_short'),
            'password.confirmed'  => __('user/register.invalid_password_confirmed_is_correct'),
            'email.unique'        => __('user/register.invalid_email_unique'),
            'phone_number.unique' => __('user/register.invalid_phone_number_unique'),
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