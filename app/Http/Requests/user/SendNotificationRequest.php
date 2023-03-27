<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest{
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
            'title'    => [
                'required',
                'min:1',
                'max:100'
            ],
            'content'    => [
                'required',
                'min:1',
                'max:5000'
            ],
        ];
    }

    /*
     * Thêm báo lỗi tùy chỉnh
     */
    public function withValidator(Validator $validator)
    {
        $validator->after( function($validator) {
            // Bắt buộc chọn đối tượng nhận
            if( empty($this->users) && empty($this->roles) ){
                $validator->errors()->add("users", __('users/notifications.invalid_receiver_is_required') );
            }

            // Không cho phép gửi mail với nội dung @name
            if( !empty($this->send_mail) ){
                if( strpos( $this->get('content'), '@name') !== false ){
                    $validator->errors()->add("content", __('users/notifications.invalid_not_support_send_mail') );
                }
            }

        });
    }

    /*
     * Trả về thông báo
     */
    public function messages(){
        return [
            '*.required'          => __('users/notifications.invalid_required'),
            '*.*'                 => __('users/notifications.data_is_incorrect'),
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