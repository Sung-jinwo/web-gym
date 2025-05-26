<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGastosRequest extends FormRequest
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
        return [
            'gast_categoria'=>'required',
            'gast_descripcion'=>'required|string|max:100',
            'gast_monto'=>'required|numeric',
            'fkuser'=>'required|exists:users,id',
            'fksede'=>'required|exists:sedes,id_sede',
        ];
    }

    public function messages(){
        return [
            'gast_categoria.required'=>'La categoria es requerida',
            'gast_descripcion.required'=>'La descripcion es requerida',
            'gast_monto.required'=>'El monto es requerido',
            'fkuser.required'=>'El usuario es requerido',
            'fksede.required'=>'El sede es requerido',
        ];
    }

}
