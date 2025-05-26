<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMembresiaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     *
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
        $user = Auth::user();
        return [
            'mem_nomb'=> 'required|string|max:255',
            'fkcategoria' => 'required',
//            'fksede' => $user->is(User::ROL_ADMIN) ? 'required' : 'nullable',
            'mem_durac' => 'required|integer|min:1',
            'mem_cost' => 'required|numeric|min:0',
            'tipo' => 'required|in:principal,adicional'
        ];
    }
    public function messages(){
        return[
            'mem_nomb.required'=> 'Se necesita nombre de Menbresias',
            'fkcategoria.required' =>'Se necesita nombre de Categoria',
//            'fksede.required' =>'Se necesita seleccionar la sede',
            'mem_durac.required' =>'Se necesita La duracion',
            'mem_cost.required' =>'Se necesita el Precio',
            'tipo'=>'Necesita el tipo de Membresia',
        ];
    }
}
