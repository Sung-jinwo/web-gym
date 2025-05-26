<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsistenciaRequest extends FormRequest
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
            // 'visi_fecha'=>'required',
            'fkalum'=>'required|numeric',
            'fksede'=>'required',
            'fkuser'=>'required'
        ];
    }

    public function messages(){
        return[
            // 'visi_fecha.required'=>'Se nesecita Registrar la fecha',
            'fkalum.required'=>'Se nesecita buscar el Alumno',
            'fksede.required'=>'Se nesecita Registrar la sede',
            'fkuser.required'=>'Se nesecita Registrar el Usuario',
        ];
    }
}
