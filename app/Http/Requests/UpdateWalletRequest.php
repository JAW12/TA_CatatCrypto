<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // if($this->filled('binance_api_key')){
        //     $rules = [
        //         'name' => 'required|string|min:3',
        //         'binance_api_key' => 'required|string',
        //         'binance_secret_key' => 'required|string'
        //     ];
        // }
        // else{
        //     $rules = [
        //         'name' => 'required|string|min:3',
        //     ];
        // }
        $rules = [
            'name' => 'required|string|min:3',
        ];
        return $rules;
    }
}
