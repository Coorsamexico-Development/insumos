<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\entrada;
use App\Models\producto;
use Illuminate\Http\Request;

class EntradaController extends Controller
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
        request()->validate([
            'codigo' => 'required',
            'categoria' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
            'cantidad' => 'required'
        ]);


        // Primero buscamos el categoria_producto
        $producto = producto::select('productos.*')
        ->where('productos.codigo','=',$request['codigo'])
        ->first();

        $categoria_producto =  categorias_producto::select('categorias_productos.*')
        ->where('categorias_productos.categoria_id','=',$request['categoria'])
        ->where('categorias_productos.producto_id','=',$producto['id'])
        ->first();

        //Creamos la nueva entrada de ese categoria producto
        entrada::create([
            'categorias_producto_id' => $categoria_producto['id'],
            'fecha' => $request['fecha'],
            'cantidad' => $request['cantidad'],
            'factura' => $request['factura']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(entrada $entrada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, entrada $entrada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(entrada $entrada)
    {
        //
    }
}
