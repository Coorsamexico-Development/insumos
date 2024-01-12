<?php

namespace App\Http\Controllers;

use App\Models\stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       return  $stages = stage::select('stages.*')
        ->join('categorias_stages','stages.categoria_stage_id','categorias_stages.id')
        ->paginate(10);
    }

    public function cambioStage(Request $request)
    {
       stage::where('id','=',$request['stage']['id'])
       ->update([
         'categoria_stage_id' => $request['stage']['categoria_stage_id']
       ]);
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
       stage::create([
         'nombre' => $request['formulario']['nombre'],
         'categoria_stage_id' => $request['formulario']['categoria_stage_id']
       ]);

       return $stages = stage::select('stages.*')
       ->join('categorias_stages','stages.categoria_stage_id','categorias_stages.id')
       ->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stage $stage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stage $stage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stage $stage)
    {
        //
    }
}
