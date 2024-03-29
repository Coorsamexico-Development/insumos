<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\entrada;
use App\Models\producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\error;

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
            //'categoria' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
            'cantidad' => 'required'
        ]);

        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = getdate();

        /*
        $categoria_producto =  categorias_producto::select('categorias_productos.*')
        ->where('categorias_productos.categoria_id','=',$request['categoria'])
        ->where('categorias_productos.producto_id','=',$producto['id'])
        ->first();
        */

        try 
        {
            // Primero buscamos el categoria_producto
            $producto = producto::select('productos.*')
            ->where('productos.codigo','=',$request['codigo'])
            ->first();
            //return $producto;

            $categoria_producto = categorias_producto::select('categorias_productos.*')
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
        catch (\Throwable $th)
        {
            //throw $th;

            error('holas');
            /*
            return response()->json([
                'success' => false,
                'data' => [
                    'dasdsa'
                ]
            ]);
            */
        }
    }

    public function downloadFactura (Request $request)
    {
        $entrada = entrada::select('entradas.*','productos.nombre as producto')
       ->where('entradas.id','=', $request['id'])
       ->join('categorias_productos','entradas.categorias_producto_id','categorias_productos.id')
       ->join('productos','categorias_productos.producto_id','productos.id')
       ->first();

       $data = [
        'entrada' => $entrada,
      ];

       $pdf = App::make('dompdf.wrapper');
       $pdf->set_option('isRemoteEnabled', true);
       $pdf->setOption('fontDir', public_path('/fonts'));
       $pdf->loadView('factura', $data);
       return $pdf->download();
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
