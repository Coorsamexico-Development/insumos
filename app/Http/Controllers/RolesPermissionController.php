<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\rolesPermission;
use Illuminate\Http\Request;

class RolesPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function setPermission (Request $request)
    {
        $role = role::select('roles.*')
        ->where('id','=',$request['rol'])
        ->first();

        if ($request['checked']) 
        {
            $role->permissions()->attach([$request['permission']]);
        } else {
            $role->permissions()->detach([$request['permission']]);
        }
        return response()->json([
            'message' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(rolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rolesPermission $rolesPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rolesPermission $rolesPermission)
    {
        //
    }
}
