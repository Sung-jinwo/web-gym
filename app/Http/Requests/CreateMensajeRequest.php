<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMensajeRequest extends FormRequest
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
            'mensa_problema' => 'nullable|string|max:100',   // Mensaje del problema, no vacío, cadena y máximo 100 caracteres
            'mensa_area' => 'nullable|in:Maquinas,Baile,Personalizado,PaqueteCompleto',            // El área debe ser uno de los valores permitidos (A, B, C, D)
            'mensa_periodo' => 'nullable|string|max:10',     // El periodo es obligatorio, de tipo cadena y máximo 10 caracteres
            'respuesta_alumno' => 'nullable|string|max:100', // Respuesta del alumno es opcional, máximo 100 caracteres
            'numero_mensaje' => 'required|integer|min:1|max:10', // El número de mensaje debe ser un valor entre 1 y 10
            'mensa_llamar' => 'required|in:0,1,2',               // Campo de llamada, opcional y booleano
            'mensa_cerrar' => 'nullable|in:S,N,P',
            'postergar' => 'nullable|in:Si,No',
            'mensa_edit' => 'nullable|date_format:Y-m',
        ];
    }


    public function messages()
    {
        return [
            'fkalum.required' => 'El ID del alumno es obligatorio.',
            'fkalum.exists' => 'El alumno seleccionado no existe.',
            'mensa_problema.required' => 'El problema del mensaje es obligatorio.',
            'mensa_area.required' => 'El área del mensaje es obligatoria.',
            'mensa_area.in' => 'El área debe ser uno de los siguientes valores: Maquinas, Baile, Personalizado, PaqueteCompleto.',
            'mensa_periodo.required' => 'El periodo del mensaje es obligatorio.',
            'respuesta_alumno.string' => 'La respuesta del alumno debe ser un texto.',
            'numero_mensaje.required' => 'El número del mensaje es obligatorio.',
            'numero_mensaje.integer' => 'El número de mensaje debe ser un número entero.',
            'numero_mensaje.min' => 'El número de mensaje debe ser al menos 1.',
            'numero_mensaje.max' => 'El número de mensaje no puede ser mayor a 10.',
            'mensa_llamar.required' => 'El campo llamar debe ser Marcado.',
            'mensa_cerrar.in' => 'El campo cerrar debe ser Si, No, Proceso.',
        ];
    }
}
