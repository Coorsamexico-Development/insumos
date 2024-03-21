<?php

namespace App\Http\Controllers;

use App\Imports\ImportBolo;
use App\Models\dt;
use App\Models\salidas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $dts = dt::select(
            'dts.*',
            'clientes.nombre as cliente',
            'stages.nombre as stage',
            'categorias_stages.id as categoria_stage',
            'destinos.nombre as destino'
            )
        ->join('clientes', 'dts.cliente_id', 'clientes.id')
        ->leftJoin('stages', 'dts.stage_id', 'stages.id')
        ->leftJoin('categorias_stages', 'stages.categoria_stage_id', 'categorias_stages.id')
        ->join('destinos', 'dts.destino_id', 'destinos.id')
        ->where('dts.activo','=',1);

        if($request->has('params'))
        {
           $request['params']['busqueda'];
           $search = strtr($request['params']['busqueda'], array("'" => "\\'", "%" => "\\%"));
           $dts->where('dts.referencia',"LIKE", "%" . $search . "%");
        }

       return $dts->paginate(10);
    }

    public function consultaInformacion (Request $request) 
    {
        //buscamos el dt
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
        ->where('dts.referencia','=',$request['dt'])
        ->first();
    }

    public function consumo(Request $request)
    {
       return salidas::selectRaw
       (
           "SUM(salidas.cantidad) AS cantidad,
           productos.nombre as producto
           "
       )
       ->join('dts','salidas.dt_id','dts.id')
       ->join('categorias_productos','salidas.categorias_producto_id','categorias_productos.id')
       ->join('productos','categorias_productos.producto_id','productos.id')
       ->where('dts.id','=',$request['dt_id'])
       ->where('categorias_productos.categoria_id','=',$request['categoria'])
       ->groupBy('productos.nombre')
       ->get();
    }


    public function verDts ()
    {
       return dt::select('dts.*')
       ->get();
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
        $request->validate([
            'bolo' => ['required'],
        ]);

        try 
        {
            Excel::import(new ImportBolo, $request['bolo']);
       
        } catch (\Throwable $th) 
        {
            //throw $th;
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(dt $dt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(dt $dt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, dt $dt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(dt $dt)
    {
        //
    }
}
