<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\categorias_producto;
use App\Models\corte_diario_historico;
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
        $categoria_producto = categorias_producto::select()
        ->where('categoria_id','=',$request['categoria_id'])
        ->where('producto_id','=',$request['producto_id'])
        ->first();

        if($request['movimiento'] == 'entradas')
        {
            $entradas = entrada::select('entradas.*')
            ->where('categorias_producto_id','=', $categoria_producto['id'])
            ->orderBy('fecha','desc')
            ->paginate(10);

            $ultimo_corte = corte_diario_historico::select('corte_diario_historicos.*')
            ->where('corte_diario_historicos.categorias_producto_id','=',$categoria_producto['id'])
            ->first();

            return ['movimientos' => $entradas , 'ultimo_corte' => $ultimo_corte];
        }
        else
        {
            $salidas = salidas::select('salidas.*', 'dts.referencia as dt' , 'stages.nombre as stage')
            ->where('categorias_producto_id','=', $categoria_producto['id'])
            ->leftjoin('dts','salidas.dt_id','dts.id')
            ->leftjoin('stages', 'dts.stage_id','stages.id')
            ->orderBy('created_at','desc')
            ->paginate(10);

            $ultimo_corte = corte_diario_historico::select('corte_diario_historicos.*')
            ->where('corte_diario_historicos.categorias_producto_id','=',$categoria_producto['id'])
            ->first();

            return ['movimientos' => $salidas , 'ultimo_corte' => $ultimo_corte];
        }

      //buscamos las entradas del producto
      /*
      $categoria_producto = categorias_producto::select()
      ->where('categoria_id','=',$request['categoria_id'])
      ->where('producto_id','=',$request['producto_id'])
      ->first();

      $entradas = entrada::select('entradas.*')
      ->where('categorias_producto_id','=', $categoria_producto['id'])
      ->orderBy('fecha','desc')
      ->get();

      $salidas = salidas::select('salidas.*', 'dts.referencia as dt' , 'stages.nombre as stage')
      ->where('categorias_producto_id','=', $categoria_producto['id'])
      ->leftjoin('dts','salidas.dt_id','dts.id')
      ->leftjoin('stages', 'dts.stage_id','stages.id')
      ->orderBy('created_at','desc')
      ->get();

      $ultimo_corte = corte_diario_historico::select('corte_diario_historicos.*')
      ->where('corte_diario_historicos.categorias_producto_id','=',$categoria_producto['id'])
      ->first();

      return ['entradas' => $entradas, 'salidas' => $salidas, 'ultimo_corte' => $ultimo_corte];
     */
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

    public function eliminarMovimiento (Request $request)
    {
       if($request['movimiento']['tipo'] == 'entrada')
       {

          entrada::where('id','=',$request['movimiento']['id'])
          ->delete();
       }
       else
       {
          salidas::where('id','=',$request['movimiento']['id'])
          ->delete();
       }
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
        $productos = categorias_producto::select(
        'productos.nombre',
        'productos.codigo')
        ->join('productos','categorias_productos.producto_id','productos.id')
        ->where('categorias_productos.categoria_id','=',$request['categoria'])
        ->get();

        $categoria = categoria::select('categorias.*')
        ->where('categorias.id','=',$request['categoria'])
        ->first();

        $data = [
            'productos' => $productos,
            'categoria' => $categoria
          ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->setOption('fontDir', public_path('/fonts'));
        $pdf->loadView('productos', $data);
        return $pdf->download();
    }
}
