<?php

namespace App\Http\Controllers;

use App\Models\dt;
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

    public function getStages (Request $request)
    {
      return stage::select('stages.*')
      ->get();
    }

    public function consultarInfoByStage (Request $request)
    {
      $stage = stage::select('stages.*')
       ->where('stages.nombre','=',$request['stage'])
       ->first();

      //return $stage['id'];

      return dt::select(
        'dts.*',
        'clientes.nombre as cliente',
        'stages.nombre as stage',
        'categorias_stages.nombre as categoria_stage',
        'destinos.nombre as destino'
       )
       ->join('clientes', 'dts.cliente_id', 'clientes.id')
       ->leftjoin('stages', 'dts.stage_id', 'stages.id')
       ->leftjoin('categorias_stages', 'stages.categoria_stage_id', 'categorias_stages.id')
       ->join('destinos', 'dts.destino_id', 'destinos.id')
       ->where('dts.stage_id','=',$stage['id'])
       ->where('dts.activo','=',1)
       ->first();
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
