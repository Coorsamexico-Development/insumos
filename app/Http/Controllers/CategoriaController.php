<?php

namespace App\Http\Controllers;

use App\Exports\MovimientosExport;
use App\Models\categoria;
use App\Models\categorias_producto;
use App\Models\entrada;
use App\Models\salidas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoriaController extends Controller
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

    /**
     * Display the specified resource.
     */
    public function show(categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categoria $categoria)
    {
        //
    }

    public function movimientosExport (Request $request)
    {
/*
        $categoria_producto = categorias_producto::select('categorias_productos.*')
        ->where('categorias_productos.categoria_id','=',$request['categoria'])
        ->first();
 
        $entradas = entrada::select('productos.nombre','entradas.cantidad','entradas.fecha')
        ->join('categorias_productos','entradas.categorias_producto_id','categorias_productos.id')
        ->join('productos','categorias_productos.producto_id','productos.id')
        ->where('entradas.categorias_producto_id','=',$categoria_producto['id'])
        ->orderBy('entradas.created_at', 'DESC')
        ->get();
 
        $salidas = salidas::select('productos.nombre','salidas.cantidad','salidas.created_at')
        ->join('categorias_productos','salidas.categorias_producto_id','categorias_productos.id')
        ->join('productos','categorias_productos.producto_id','productos.id')
        ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
        ->orderBy('salidas.created_at','DESC')
        ->get();

        $movimientos = [];

        for ($i=0; $i < count($entradas) ; $i++) 
        { 
            $entradas[$i]->tipo ='entrada';
           array_push($movimientos, $entradas[$i]);
        }

        for ($x=0; $x < count($salidas) ; $x++) 
        { 
            $salidas[$x]->tipo = 'salida';
            array_push($movimientos, $salidas[$x]);
        }
    
        usort($movimientos, function ($a, $b) 
        {
            return strcmp($b["created_at"], $a["created_at"]);
        });

        return $movimientos;
*/
        return Excel::download(new MovimientosExport($request['categoria']), 'reporte.xlsx');
    }
}
