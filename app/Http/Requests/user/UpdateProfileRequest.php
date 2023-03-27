<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest{
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
                function($attribute, $value, $fail) {
                    if( \App\User::where('id', '!=', $this->id)->where('phone_number', $value)->exists() ){
                        return $fail( __('user/register.invalid_phone_number_unique') );
                    }
                }
            ],
            'email'        => [
                'required',
                'regex:/^.+@.+$/i',
                'max:50',
                function($attribute, $value, $fail) {
                    if( \App\User::where('id', '!=', $this->id)->where('email', $value)->exists() ){
                        return $fail( __('user/register.invalid_email_unique') );
                    }
                }
            ],
            'id' => function($attribute, $userId, $fail) {
                $error = false;
                if( empty($userId) ){
                    $error = true;
                }else{
                    // Không có quyền chỉnh tài khoản người khác
                    if( $userId == \Auth::id() ){

                    }else{
                        if( !\Permission::has('user_manager') ){
                            $error = true;
                        }
                    }

                    // Chỉ admin mới có thể chỉnh tài khoản của chức admin
                    if( \Permission::has('admin', $userId) && !\Permission::has('admin') ){
                        $error = true;
                    }
                }
                if( $error ){
                    return $fail( __('user/register.data_is_incorrect') );
                }
            },
            'role_id' => function($attribute, $value, $fail) {
                // Chỉ admin mới có thể update chức vụ
                if( isset($value) && !\Permission::has('admin') ){
                    return $fail( __('user/register.data_is_incorrect') );
                }
            },
        ];
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required'          => __('user/register.invalid_required'),
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