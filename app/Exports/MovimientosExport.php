<?php

namespace App\Exports;

use App\Models\categorias_producto;
use App\Models\entrada;
use App\Models\salidas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovimientosExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(int $categoria)
    {
        $this->categoria = $categoria;
    }
    //$this->categoria

    public function collection()
    {
        //
        $categoria_producto = categorias_producto::select('categorias_productos.*')
        ->where('categorias_productos.categoria_id','=',$this->categoria)
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



       return collect($movimientos);

    }

    public function headings(): array
    {
        return 
        [
          "Producto",
          "Cantidad",
          "Fecha",
          "Tipo de movimiento",
        ];
    }
}
