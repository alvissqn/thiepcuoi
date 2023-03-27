<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest{
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
            'password'    => [
                'required',
                'min:1',
                'max:30'
            ],
            'id' => function($attribute, $userId, $fail) {
                $error = false;
                if( empty($userId) ){
                    $error = true;
                }else{
                    // Không có quyền đổi mật khẩu tài khoản khác
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
            }
        ];
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required'          => __('user/register.invalid_required'),
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