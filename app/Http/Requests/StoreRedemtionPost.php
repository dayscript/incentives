<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RedemptionMaxValue;

class StoreRedemtionPost extends FormRequest
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
          'entity_id'     => 'required|numeric',
          'value'         => 'required|numeric|min:1|points:'.$this->input('entity_id'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'entity_id.required' => 'Hubo un error, intentalo más tarde',
            'entity_id.numeric'  => 'Solo se admiten números',
            'value.required'     => 'Debes Escribir el número de Kokoripesos a redimir',
            'value.numeric'      => 'Solo se admiten números',
            'points'             => 'No es posible redimir ese monto de kokoripesos',
            'min'                => 'No es posible redimir ese monto de kokoripesos'

        ];
    }
}
