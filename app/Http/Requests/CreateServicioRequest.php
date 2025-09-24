<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $alumno = $this->route('alumno');
        $alumnoId = $alumno? $alumno->id_alumno : null;

//        \Log::info('ID del alumno:', ['id' => $alumnoId]);
        return [
            'alum_codigo' => 'nullable|unique:alumno,alum_codigo,' . $alumnoId . ',id_alumno',
            'alum_nombre' => 'required',
            'alum_apellido' => 'required',
            'alum_direccion' => 'nullable',
            'alum_correro' => 'nullable|email',
            'alum_telefo' => 'required',
            'alum_numDoc' => 'nullable',
            'alum_documento' => 'nullable',
            'fksexo' => 'required|exists:sexo,id_sexo',
            'fksede' => 'required|exists:sedes,id_sede',
            'fkuser' => 'required',
            'fecha_nac' => 'required|date|before_or_equal:today ',
            'alum_img' => 'nullable|image|mimes:jpg,png|dimensions:width=708,height=708',

        ];
    }

    public function messages(){
        return[
            // 'alum_codigo.required' => 'Se necesita un Código para el alumno',
            'alum_img.required' => 'No es el Formato indicado',
            'alum_nombre.required' => 'Se necesita un Nombre para el Alumno',
            'alum_apellido.required' => 'Se necesita un Apellido para el Alumno',
            'alum_correro.required' => 'Se necesita un correo para el Alumno',
            'fksexo.required' => 'Se necesita Seleccionar el sexo del Alumno',
            'fkuser.required' => 'Se necesita Seleccionar un Usuario',
            'alum_telefo.required' => 'Se necesita el teléfono del Alumno',
            'fecha_nac.required' => 'Se necesita la edad del Alumno',
            'fecha_nac.before_or_equal' => 'La fecha de nacimiento no puede ser una fecha futura.',
            'fksede.required' => 'Seleccione la sede de Registro',
            'alum_img.image' => 'El archivo debe ser una imagen válida.',
            'alum_img.mimes' => 'Solo se permiten imágenes en formato JPG o PNG.',
            'alum_img.dimensions' => 'La imagen debe tener exactamente 708x708 píxeles (equivalente a 6x6 cm a 300 DPI).',
        ];
    }
}
