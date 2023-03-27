<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\UserServices;

class ChangeAvatarRequest extends FormRequest{
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
            'avatar'     => [
                'required',
                'mimes:jpg,jpeg,png',
                'max:10000'
            ],
        ];
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required' => __('user/register.invalid_required'),
            '*.mimes'    => __('user/profile.change_avatar_file_is_disallow'),
            '*.max'      => __('user/profile.change_avatar_file_is_large'),
            '*.*'        => __('user/register.data_is_incorrect'),
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