<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRichRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //send_dk, take_back_dk, send_dn, take_back_dn
        //regex:/send_dk|take_back_dk|send_dn|take_back_dn/
        return [
           'operate_type' => 'required|string|in:send_dk,take_back_dk,send_dn,take_back_dn',
           'operate_num' => 'required|numeric|min:1'
        ];
    }
}
