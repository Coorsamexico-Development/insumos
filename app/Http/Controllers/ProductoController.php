<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\entrada;
use App\Models\producto;
use App\Models\salidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      //Tomar entrasdas o salidas del procuto
      //buscamos las entradas del producto
      $categoria_producto = categorias_producto::select()
      ->where('categoria_id','=',$request['producto']['categoria_id'])
      ->where('producto_id','=',$request['producto']['producto_id'])
      ->first();

      $entradas = entrada::select('entradas.*')
      ->where('categorias_producto_id','=', $categoria_producto['id'])
      ->orderBy('fecha','desc')
      ->get();

      $salidas = salidas::select('salidas.*', 'dts.referencia as dt' , 'stages.nombre as stage')
      ->where('categorias_producto_id','=', $categoria_producto['id'])
      ->join('dts','salidas.dt_id','dts.id')
      ->join('stages', 'dts.stage_id','stages.id')
      ->orderBy('created_at','desc')
      ->get();

      return ['entradas' => $entradas, 'salidas' => $salidas];

     /*
       if($request['tipo'] == 'entradas')
       {
          //buscamos las entradas del producto
          $categoria_producto = categorias_producto::select()
          ->where('categoria_id','=',$request['producto']['categoria_id'])
          ->where('producto_id','=',$request['producto']['producto_id'])
          ->first();

          if($categoria_producto !== null)
          {
            return $entradas = entrada::select('entradas.*')
            ->where('entradas.categorias_producto_id','=', $categoria_producto['id'])
            ->get();
          }
       }
       else
       {
        $categoria_producto = categorias_producto::select()
        ->where('categoria_id','=',$request['producto']['categoria_id'])
        ->where('producto_id','=',$request['producto']['producto_id'])
        ->first();
         //buscamos las salidas del producto
         if($categoria_producto !== null)
         {
            return $salidas = salidas::select('salidas.*')
            ->where('salidas.categorias_producto_id','=', $categoria_producto['id'])
            ->get();
         }
       }
       */
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
    public function show(producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(producto $producto)
    {
        //
    }

    public function pdfCodes (Request $request)
    {
        $productos = producto::all();
        $data = [
            'productos' => $productos
          ];
        $pdf = App::make('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->loadView('productos', $data);
        return $pdf->stream();
    }
}
