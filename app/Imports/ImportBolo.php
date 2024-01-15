<?php

namespace App\Imports;

use App\Models\cliente;
use App\Models\destino;
use App\Models\dt;
use App\Models\stage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ImportBolo implements ToModel, WithHeadingRow, SkipsEmptyRows
{  
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
         //dd($row);
        //Validamos que las columnas no esten vaxias
        if($row[6] !== null &&  $row[7] !== null /*&& $row['stage'] !== null*/ )
        {
            //dd($row);
            //Primero creamos los clientes si ya existen lo tomamos
            $cliente = cliente::select('clientes.*')
            ->where('nombre', $row[6])
            ->first();
            //Buscamos el destino si existe sino lo creamos
            $destino = destino::select('destinos.*')
            ->where('nombre','=',$row[7])
            ->first();
    
            if($cliente == null) //sino existe lo creamos el cliente
            {
               $cliente = cliente::updateOrCreate([
                'nombre' => $row[6]
               ]);
            }
    
            if($destino == null) //sino existe lo creamos el destino
            {
               $destino = destino::updateOrCreate([
                'nombre' => $row[7]
               ]);
            }

            if($row[23] !== null)
            {
              //Buscamos el stage si esxite lo tomamos sino los creamos
              $stage = stage::select('stages.*')
              ->where('nombre', '=' ,$row[23])
              ->first();

              if($stage == null) //sino existe lo creamos el stage
              { 
                 $stage = stage::updateOrCreate([
                   'nombre' => $row[23],
                   'categoria_stage_id' => 1
                  ]);

                  dt::updateOrCreate([
                     'referencia' => $row[4],
                     'cliente_id' => $cliente['id'],
                     'stage_id' => $stage['id'],
                     'destino_id' => $destino['id']
                  ]);
              }
            }
            else
            {
              // dd($row);     
               dt::updateOrCreate([
                  'referencia' => $row[4],
                  'cliente_id' => $cliente['id'],
                  'destino_id' => $destino['id']
               ]);

            }
    
        }
    }

    public function headingRow(): int
    {
        return 5;
    }

    /*
    public function rules(): array
    {
        return [
            'cliente' => [
                'required',
                'string',
            ],
           'destino' => [
            'required',
            'string',
           ],
           'load' => [
            'required',
            'string',
           ],
        ];
    }
    */

    public function isEmptyWhen(array $row): bool
    {
        return $row[4] === 'LOAD';
    }
    
    
}
