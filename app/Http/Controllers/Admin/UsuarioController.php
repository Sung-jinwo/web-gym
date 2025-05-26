<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $users = User::orderBy('rol')->get();

        return view('admin.users.usersvi', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.usersedit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $password = $request->input('password');

        if ($password) {
            $user->password = bcrypt($password);
            $user->save();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('estado', "La contraseña del usuario $user->name fue actualizado correctamente");
    }
}
