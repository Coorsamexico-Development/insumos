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
        return Excel::download(new MovimientosExport($request['categoria']), 'reporte_movimientos.xlsx');
    }
}
