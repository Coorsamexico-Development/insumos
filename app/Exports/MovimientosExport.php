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
        $categorias_productos = categorias_producto::select('categorias_productos.*')
            ->where('categorias_productos.categoria_id', '=', $this->categoria)
            ->get();

        $movimientos = [];

        for ($i = 0; $i < count($categorias_productos); $i++) {
            $categoria_producto = $categorias_productos[$i];

            $entradas = entrada::select('productos.nombre', 'entradas.cantidad', 'entradas.fecha')
                ->join('categorias_productos', 'entradas.categorias_producto_id', 'categorias_productos.id')
                ->join('productos', 'categorias_productos.producto_id', 'productos.id')
                ->where('entradas.categorias_producto_id', '=', $categoria_producto['id'])
                ->orderBy('entradas.created_at', 'DESC')
                ->get();

            $salidas = salidas::select(
                'productos.nombre',
                'salidas.cantidad',
                'salidas.created_at',
                'dts.referencia',
                'stages.nombre as stage',
                'clientes.nombre as cliente'
            )
                ->join('categorias_productos', 'salidas.categorias_producto_id', 'categorias_productos.id')
                ->join('productos', 'categorias_productos.producto_id', 'productos.id')
                ->leftJoin('dts', 'salidas.dt_id', 'dts.id')
                ->leftJoin('stages', 'dts.stage_id', 'stages.id')
                ->leftJoin('clientes', 'clientes.id', 'dts.cliente_id')
                ->where('salidas.categorias_producto_id', '=', $categoria_producto['id'])
                ->orderBy('salidas.created_at', 'DESC')
                ->get();

            for ($x = 0; $x < count($entradas); $x++) {
                $entradas[$x]->dt = '-';
                $entradas[$x]->stage = '-';
                $entradas[$x]->tipo = 'Entrada';
                array_push($movimientos, $entradas[$x]);
            }

            for ($y = 0; $y < count($salidas); $y++) {

                $salidas[$y]->tipo = 'Salida';
                array_push($movimientos, $salidas[$y]);
            }
        }

        usort($movimientos, function ($a, $b) {
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
                "DT",
                "Stage",
                'Cliente',
                "Tipo de movimiento",
            ];
    }
}
