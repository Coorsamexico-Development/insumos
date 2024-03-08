<?php

namespace App\Exports;

use App\Models\corte_diario_historico;
use App\Models\corte_mensual_historico;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CortesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(int $categoria)
    {
        $this->categoria = $categoria;
    }

    public function collection()
    {
       return corte_diario_historico::select(
        'productos.nombre as producto',
        'corte_diario_historicos.cantidad_inicial as cantidad',
        'corte_diario_historicos.fecha as fecha'
       )
        ->join('categorias_productos','corte_diario_historicos.categorias_producto_id','categorias_productos.id')
        ->join('productos','categorias_productos.producto_id','productos.id')
        ->where('categorias_productos.categoria_id','=',$this->categoria)
        ->get();

    }

    public function headings(): array
    {
        return 
        [
          "Producto",
          "Cantidad",
          "Fecha de corte",
        ];
    }
}
