<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\cliente;
use App\Models\dt;
use App\Models\producto;
use App\Models\salidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;

class SalidasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $categoria_producto = categorias_producto::select('categorias_productos.*')
        ->where('categorias_productos.categoria_id','=',$request['categoria_id'])
        ->where('categorias_productos.producto_id','=',$request['producto_id'])
        ->first();

        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();
        $month = '';
        if($fecha_actual['mon'] < 10)
        {
          $month = '0'.$fecha_actual['mon'];
        }
        else
        {
          $month = $fecha_actual['mon'];
        }

        $newFechaActual = $fecha_actual['year'].'-'.$month;
        

        $salidasTotal = [];     
        $salidas = [];
        if(request()->has('fecha'))
        {
            $salidas = salidas::
            selectRaw("
                       salidas.cantidad,
                       DATE_FORMAT(salidas.created_at, '%Y-%m-%d') AS new_date, 
                       YEAR(salidas.created_at) AS year, 
                       MONTH(salidas.created_at) AS month,
                       clientes.nombre as cliente
                       "
                      )
           ->join('dts','salidas.dt_id','dts.id')
           ->join('clientes','dts.cliente_id','clientes.id')
           ->whereDate('salidas.created_at','LIKE','%'.$request['fecha'].'%')
           ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
           ->orderBy('new_date')
           ->orderBy('cliente')
           ->get();

          $salidasTotal = salidas::
            selectRaw("
                       SUM(salidas.cantidad) as total,
                       DATE_FORMAT(salidas.created_at, '%Y-%m-%d') AS new_date, 
                       YEAR(salidas.created_at) AS year, 
                       MONTH(salidas.created_at) AS month,
                       clientes.nombre as cliente
                       "
                      )
           ->join('dts','salidas.dt_id','dts.id')
           ->join('clientes','dts.cliente_id','clientes.id')
           ->whereDate('salidas.created_at','LIKE','%'.$request['fecha'].'%')
           ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
           ->orderBy('salidas.created_at')
           ->groupBy('new_date')
           ->get();
        }
        else
        {
            $salidas = salidas::
            selectRaw("
                       salidas.cantidad,
                       DATE_FORMAT(salidas.created_at, '%Y-%m-%d') AS new_date, 
                       YEAR(salidas.created_at) AS year, 
                       MONTH(salidas.created_at) AS month,
                       clientes.nombre as cliente
                       "
                      )
           ->join('dts','salidas.dt_id','dts.id')
           ->join('clientes','dts.cliente_id','clientes.id')
           ->whereDate('salidas.created_at','LIKE','%'.$newFechaActual.'%')
           ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
           ->orderBy('new_date')
           ->orderBy('cliente')
           ->get();

           $salidasTotal = salidas::
           selectRaw("
                      SUM(salidas.cantidad) as total,
                      DATE_FORMAT(salidas.created_at, '%Y-%m-%d') AS new_date, 
                      YEAR(salidas.created_at) AS year, 
                      MONTH(salidas.created_at) AS month,
                      clientes.nombre as cliente
                      "
                     )
          ->join('dts','salidas.dt_id','dts.id')
          ->join('clientes','dts.cliente_id','clientes.id')
          ->whereDate('salidas.created_at','LIKE','%'.$newFechaActual.'%')
          ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
          ->orderBy('salidas.created_at')
          ->groupBy('new_date')
          ->get();
        }

        $clientes = cliente::select('clientes.*')
        ->orderBy('clientes.nombre')
        ->get();
        
        return ['salidas' => $salidas, 'clientes' => $clientes,'salidasTotales' => $salidasTotal];
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
        $request->validate([
            'dt' =>  ['required'],
            'codigo' => ['required'],
            "cantidad" => ['required']
        ]);
        //
        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();
        //Primero buscamos el dt en dado caso de meterlo incompletop
        $dt = dt::select('dts.*')
        ->where('dts.referencia','LIKE','%'.$request['dt'].'%')
        ->first();

        //return $request;
        try 
        {
            if($dt !== null) //si encontramos el dt pasamos a crear
            {
                //Buscamos el producto
                 $producto =  producto::select('productos.*')
                ->where('productos.codigo','=',$request['codigo'])
                ->first();
                //
                if($producto !== null)
                {
                     $categoria_producto = categorias_producto::select('categorias_productos.*')
                    //->where('categorias_productos.categoria_id','=',$request['categoria'])
                    ->where('categorias_productos.producto_id','=',$producto['id'])
                    ->first();
    
                    salidas::create([
                        'categorias_producto_id' => $categoria_producto['id'],
                        'dt_id' => $dt['id'],
                        'cantidad' => $request['cantidad']
                    ]);
                }
            }
            else//sino no se crea asociado a ningun dt
            {
               //Buscamos el producto
               $producto =  producto::select('productos.*')
               ->where('productos.codigo','=',$request['codigo'])
               ->first();
               //
               if($producto !== null)
               {
                   $categoria_producto = categorias_producto::select('categorias_productos.*')
                   //->where('categorias_productos.categoria_id','=',$request['categoria'])
                   ->where('categorias_productos.producto_id','=',$producto['id'])
                   ->first();
             
                   salidas::create([
                       'categorias_producto_id' => $categoria_producto['id'],
                       'cantidad' => $request['cantidad']
                   ]);
               }
            }
        } catch (\Throwable $th) 
        {
            //throw $th;
          error('holas');
        }
    }

    public function salidasByClienteDate (Request $request) 
    {
       $categoria_producto = categorias_producto::select('categorias_productos.*')
       ->where('categorias_productos.categoria_id','=',$request['categoria_id'])
       ->where('categorias_productos.producto_id','=',$request['producto_id'])
       ->first();

       return salidas::selectRaw(
        "SUM(salidas.cantidad) AS cantidad,
         DATE_FORMAT(salidas.created_at, '%Y-%m-%d') AS new_date,
         dts.referencia as dt,
         clientes.nombre as cliente
         ")
         ->join('dts','salidas.dt_id','dts.id')
         ->join('clientes','dts.cliente_id','clientes.id')
         ->where('clientes.nombre','=',$request['cliente'])
         ->whereDate('salidas.created_at','LIKE','%'.$request['fecha'].'%')
         ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
         ->groupBy('dts.referencia')
         ->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(salidas $salidas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(salidas $salidas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, salidas $salidas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(salidas $salidas)
    {
        //
    }
}
