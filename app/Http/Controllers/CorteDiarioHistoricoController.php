<?php

namespace App\Http\Controllers;

use App\Models\categorias_producto;
use App\Models\corte_diario_historico;
use App\Models\entrada;
use App\Models\salidas;
use Illuminate\Http\Request;

class CorteDiarioHistoricoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias_productos = categorias_producto::all();
        //por cada categoria producto checaremos lasa salids y entradas para el calculo del sig corte
        for ($i=0; $i < count($categorias_productos) ; $i++) 
        { 
            $categoria_producto = $categorias_productos[$i];
            //las entradas y salidas deben ser despues de la hora del ultimo corte por tanto ocupamos 
            //saber el ultimo corte
            $ultimo_corte = corte_diario_historico::select('corte_diario_historicos.*')
            ->where('corte_diario_historicos.activo','=',1)
            ->where('corte_diario_historicos.categorias_producto_id','=',$categoria_producto['id'])
            ->orderBy('corte_diario_historicos.id', 'DESC')
            ->first();

            //return $ultimo_corte;

            $fechaactual = getdate();

            if($ultimo_corte == null) //sino exsite el corte mas reciente
            {
                date_default_timezone_set('America/Mexico_City');
                $fechaactual = getdate();
                $añoActual = $fechaactual['year'];
                $mesActual = $fechaactual['mon'];
                $diaActual= $fechaactual['mday'];
                if($mesActual < 10)
                {
                  $mesActual = '0'.$mesActual;
                }
   
                if($diaActual < 10)
                {
                  $diaActual = '0'.$diaActual;
                }

                  //si el producto no tiene historico de entradas y salidas la cantidad inicial sera 
                  $salidas = salidas::select('salidas.*')
                  ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
                  //->where('salidas.created_at','>',$ultimo_corte['fecha'])
                  ->get();
                    
                  $entradas = entrada::select('entradas.*')
                  ->where('entradas.categorias_producto_id','=',$categoria_producto['id'])
                 // ->where('entradas.fecha','>',$ultimo_corte['fecha'])
                  ->get();
                    
                  //return $entradas;
                  
                  $totalEntradas = 0;
                  $totalSalidas = 0;
                  //Recorremos las entradas para hacer la sumatoria
                  if(count($entradas) > 0)
                  {
                      for ($x=0; $x < count($entradas) ; $x++) 
                      { 
                         $entrada = $entradas[$x];
                         $totalEntradas += $entrada['cantidad'];
                      }
                  }
                    
                  if(count($salidas) > 0)
                  {
                      for ($s=0; $s < count($salidas) ; $s++) 
                      { 
                        $salida = $salidas[$s];
                        $totalSalidas += $salida['cantidad'];
                      }
                  }
                    
                  $total = ($totalEntradas) - $totalSalidas;
   
               
                $newFechaActual = $añoActual.'-'.$mesActual.'-'.$diaActual.' '.$fechaactual['hours'].':'.$fechaactual['minutes'].':'.$fechaactual['seconds'];
                $new_corte = corte_diario_historico::create([
                   'categorias_producto_id' => $categoria_producto['id'],
                   'fecha' => $newFechaActual,
                   'cantidad_inicial' => $total
                ]);
            }
            else
            {
                //si el producto no tiene historico de entradas y salidas la cantidad inicial sera 
                $salidas = salidas::select('salidas.*')
                ->where('salidas.categorias_producto_id','=',$categoria_producto['id'])
                ->where('salidas.created_at','>',$ultimo_corte['fecha'])
                ->get();

                $entradas = entrada::select('entradas.*')
                ->where('entradas.categorias_producto_id','=',$categoria_producto['id'])
                ->where('entradas.fecha','>',$ultimo_corte['fecha'])
                ->get();

                //return $entradas;
                
                $totalEntradas = 0;
                $totalSalidas = 0;
                //Recorremos las entradas para hacer la sumatoria
                if(count($entradas) > 0)
                {
                    for ($x=0; $x < count($entradas) ; $x++) 
                    { 
                       $entrada = $entradas[$x];
                       $totalEntradas += $entrada['cantidad'];
                    }
                }

                if(count($salidas) > 0)
                {
                    for ($s=0; $s < count($salidas) ; $s++) 
                    { 
                      $salida = $salidas[$s];
                      $totalSalidas += $salida['cantidad'];
                    }
                }

                $total = ($ultimo_corte['cantidad_inicial']+$totalEntradas) - $totalSalidas;
                date_default_timezone_set('America/Mexico_City');
                $fechaactual = getdate();
                $añoActual = $fechaactual['year'];
                $mesActual = $fechaactual['mon'];
                $diaActual= $fechaactual['mday'];
                if($mesActual < 10)
                {
                  $mesActual = '0'.$mesActual;
                }
   
                if($diaActual < 10)
                {
                  $diaActual = '0'.$diaActual;
                }
                $newFechaActual = $añoActual.'-'.$mesActual.'-'.$diaActual.' '.$fechaactual['hours'].':'.$fechaactual['minutes'].':'.$fechaactual['seconds'];
                //return $newFechaActual;
                //desactivamos los anteriores cortes
                corte_diario_historico::whereDate('corte_diario_historicos.fecha','<',$ultimo_corte['fecha'])
                ->where('corte_diario_historicos.categorias_producto_id','=',$categoria_producto['id'])
                ->update([
                    'activo' => 0
                ]);

                $new_corte = corte_diario_historico::create([
                 'categorias_producto_id' => $categoria_producto['id'],
                 'fecha' => $newFechaActual,
                 'cantidad_inicial' => $total
              ]);
            }
        }

        return 'corte hecho';
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
    public function show(corte_diario_historico $corte_diario_historico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(corte_diario_historico $corte_diario_historico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, corte_diario_historico $corte_diario_historico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(corte_diario_historico $corte_diario_historico)
    {
        //
    }
}
