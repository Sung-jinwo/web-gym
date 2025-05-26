<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Membresias;

class CreatePagosRequests extends FormRequest
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
    protected function prepareForValidation()
    {
        // Obtener el valor de 'monto_pagado' y 'pago'
        $montoPagado = $this->input('monto_pagado');
        $pago = $this->input('pago');

        // Si 'monto_pagado' es null o 0, asignarle el valor de 'pago'
        if (is_null($montoPagado) || $montoPagado == 0) {
            $this->merge([
                'monto_pagado' => $pago,
            ]);
        }
    }
    public function rules()
    {
        $rules = [
            'fkuser' => 'required|exists:users,id',
            'fkmem' => 'required|exists:membresias,id_mem',
            'pag_inicio' => 'nullable|date',
            'pag_fin' => 'nullable|date|after:pag_inicio',
            'pag_entre' => 'nullable|string',
            'pago' => 'required|numeric|min:0',
            'fkmetodo' => 'required|exists:metodos_pago,id_metod',
            'fksede' => 'required|exists:sedes,id_sede',
            'fkalum' => 'nullable|exists:alumno,id_alumno|required_unless:fkmem,'.getRutina()->id_mem,
            'monto_pagado' => 'nullable',
            'estado_pago' => 'required|in:completo,incompleto',
            'fecha_limite_pago' => 'nullable|required_if:estado_pago,incompleto|date',
            'saldo_pendiente' => 'nullable|required_if:estado_pago,incompleto|numeric|min:0',
        ];

        if ($this->has('fkmem')) {
            $membresia = Membresias::with('categoria_m')->find($this->input('fkmem'));

            if ($membresia && $membresia->categoria_m) {
                if ($membresia->categoria_m->nombre_m !== 'Rutina') {
                    $rules['fkalum'] = 'required|exists:alumno,id_alumno';
                }
            }
        }

        return $rules;
    }

    public function messages(){
        return [
            'fkalum.required' => 'El alumno es obligatorio para membresías (presionar botón Buscar).',
            'fkalum.exists' => 'El alumno seleccionado no existe en nuestros registros.',
            'fecha_limite_pago.required_if' => 'La fecha límite es obligatoria para pagos incompletos.',
            'saldo_pendiente.required_if' => 'El saldo pendiente es obligatorio para pagos incompletos.',
        ];
    }




}
