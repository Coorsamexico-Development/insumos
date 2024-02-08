<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\dt;
use App\Models\producto;
use App\Models\salidas;
use Illuminate\Http\Request;

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
        ->where('categorias_productos.categoria_id','=',$request['producto']['categoria_id'])
        ->where('categorias_productos.producto_id','=',$request['producto']['producto_id'])
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

        return $salidas = salidas::select('salidas.*')
        ->whereDate('salidas.created_at','LIKE','%'.$newFechaActual.'%')
        ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
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
        $request->validate([
            'dt' =>  ['required'],
            'codigo' => ['required'],
            "cantidad" => ['required']
        ]);
        //
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
