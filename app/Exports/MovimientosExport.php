<?php

namespace App\Exports;

use App\Models\entrada;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovimientosExport implements FromCollection
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
        return entrada::all();
    }

    public function headings(): array
    {
        return 
        [
          "Producto",
          "Tipo de movimiento"
        ];
    }
}
