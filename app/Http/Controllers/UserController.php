<?php

namespace App\Http\Controllers;

use App\Models\permission;
use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    //
    public function index (Request $request)
    {
        $usuarios = User::select('users.*')
        ->paginate(10);

        $roles = role::select('roles.*')
        ->get();

        $permisos = permission::select('permissions.*')
        ->get();

        return Inertia::render('Users/UserIndex',
        [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'permisos' => $permisos
        ]);
    }

    public function store (Request $request)
    {
        request()->validate([
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'email' => 'required',
            'contraseña' => 'required'
        ]);

        User::create([
            'name' => $request['nombre'],
            'apellido_paterno'=> $request['ap_paterno'],
            'apellido_materno' => $request['ap_materno'],
            'email'=> $request['email'],
            'password' => Hash::make($request['contraseña']),
        ]);

        redirect()->back();
    }
}
