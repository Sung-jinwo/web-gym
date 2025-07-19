<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVentaRequest extends FormRequest
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
            'fkusers' => 'required|exists:users,id',
            'venta_entre' => 'nullable|string',
            'fkalum' => 'nullable|exists:alumno,id_alumno',
            'fksede' => 'required|exists:sedes,id_sede',
            'fkmetodo' => 'required|exists:metodos_pago,id_metod',
            'estado_venta'=> 'required',
            'venta_fecha'=> 'nullable|date_format:Y-m-d',
            'venta_pago' => 'nullable',
            'venta_saldo' => 'nullable',
            'venta_incrementado' => 'nullable',
            // 'fkproducto' => 'required|exists:productos,id_productos',
            'cantidad' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'fkusers.required' => 'El usuario es obligatorio.',
            'fkusers.exists' => 'El usuario seleccionado no es válido.',
            'fkalum.exists' => 'El alumno Ingresado no es válido.',
            'fksede.required' => 'La sede es obligatoria.',
            'fksede.exists' => 'La sede seleccionada no es válida.',
            'fkmetodo.required' => 'El Tipo de Pago es obligatoria.',
            'fkmetodo.exists' => 'El Metodo de pago seleccionado no es válido.',
            'fkmetodo.required' => 'El Estado del pago no fue seleccionado no es válido.',
            'estado_venta.required' => 'El estado de la venta es obligatorio.',
            'fkproducto.required' => 'El producto es obligatorio.',
            'fkproducto.exists' => 'El producto seleccionado no es válido.',
            'cantidad.required' => 'Debe ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima permitida es 1.'
        ];
    }
}
