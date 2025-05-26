<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoriaMRequest extends FormRequest
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
            'fkuser'=> 'required',
            'nombre_m'=> 'required|string|max:255'
        ];
    }

    public function messages(){
        return[
            'fkuser.required'=> 'Se necesita ingresar un usuario',
            'nombre_m.required'=> 'Se necesita el nombre de la Categoria',
        ];
   
    }

}
