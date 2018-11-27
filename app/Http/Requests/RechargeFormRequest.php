<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechargeFormRequest extends FormRequest
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
        return [
            'id'=>'required|numeric|min:1',
            'status'=>'required|string|in:pass,reject',
            'note'=>'nullable|string|required_if:status,reject',
            'real_coin_num'=>'nullable|numeric|required_if:status,pass'
        ];
    }
}
