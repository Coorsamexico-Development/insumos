<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\producto;
use Illuminate\Http\Request;

class CategoriasProductoController extends Controller
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
        //Creamos primero el producto 
        $codigo_random = random_int(1000, 9999);
        //Si existe algun codigo se genera otro nuevo hasta que sea un unico codigo
        $producto_a_comprobar = producto::select('productos.*')
        ->where('productos.codigo','=',$codigo_random)
        ->where('productos.activo','=',1)
        ->first();

        if($producto_a_comprobar !== null) //si existe hay que actualizarlo
        {
            //actualizamos producto
            $producto_a_actualizar = producto::where('id','=',$producto_a_comprobar['id'])
            ->update([
                'nombre' => $request['nombre'],
                'codigo' => $codigo_random //generado por aqui
            ]);

              //creamos la interseccion del producto con la categoria
              categorias_producto::updateOrCreate([
                'categoria_id' => $request['categoria'],
                'producto_id' => $producto_a_actualizar['id'],
                'stock_minimo' => $request['stock'],
                'tiempo_de_reestock' => $request['tiempo_resturtido']
              ]);
        }
        else //sino existe se crea
        {
            $producto_nuevo = producto::updateOrCreate([
                'nombre' => $request['nombre'],
                'codigo' => $codigo_random //generado por aqui
              ]);
      
              //creamos la interseccion del producto con la categoria
              categorias_producto::updateOrCreate([
                'categoria_id' => $request['categoria'],
                'producto_id' => $producto_nuevo['id'],
                'stock_minimo' => $request['stock'],
                'tiempo_de_reestock' => $request['tiempo_resturtido']
              ]);
        }

        redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(categorias_producto $categorias_producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categorias_producto $categorias_producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, categorias_producto $categorias_producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categorias_producto $categorias_producto)
    {
        //
    }
}
