<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductoRequest extends FormRequest
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
            'prod_nombre'=>'required',
            'fkcategoria'=>'required',
            'prod_descripcion' => 'nullable',
            'prod_cantidad'=>'required|integer',
            'prod_precio'=>'required|numeric',
            'fksede'=>'required',
            'fkusers'=>'required',
            'prod_img' =>'nullable|mimes:jpg,png',
        ];
    }

    public function messages(){
        return[
            'prod_nombre.required'=>'Se nesecita Registrar el Nombre',
            'fkcategoria.required'=>'Se nesecita Registrar la Categoria',
            'prod_cantidad.required'=>'Se nesecita Registrar la Cantidad',
            'prod_precio.required'=>'Se nesecita Registrar el Precio',
            'sede.required'=>'Se nesecita Registrar la Sede',
            'fkusers.required'=>'Se nesecita Registrar el Usuario',
            'prod_img.mimes' => 'La imagen debe ser en formato jpg o png.',
        ];
    }
}
