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
        $usuarios = User::select('users.*', 'roles.nombre as role_name')
        ->leftJoin('roles','users.role_id','roles.id')
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

    public function update(Request $request)
    {
        //
        $request->validate([ //validaciones
            'id' => 'required',
            'email' => 'required',
            'nombre' => 'required',
            'ap_paterno' => 'required',
            'ap_materno' => 'required',
            'contraseña' => 'required',
            'role_id' => 'required',
        ]);

      $user =  User::where('id','=',$request['id'])
        ->update([
            'email' => $request['email'],
            'name' => $request['nombre'],
            'apellido_paterno' => $request['ap_paterno'],
            'apellido_materno' => $request['ap_materno'],
            'role_id' => $request['role_id'],
            'password' => Hash::make($request['contraseña'])
        ]);
        
        return redirect()->back();
    }
}
