<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\UserServices;

class ChangePasswordRequest extends FormRequest{
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
            'password_old'     => [
                'required',
                function($attribute, $value, $fail) {
                    if( !UserServices::verifyPassword($value, \Auth::user()->password ) ){
                        return $fail( __('user/profile.old_password_is_incorrect') );
                    }
                }
            ],
            'password'     => [
                'required',
                'min:6',
                'confirmed'
            ],
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