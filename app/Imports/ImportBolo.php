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
        //return dd($row);
        //Validamos que las columnas no esten vaxias
        if($row['cliente'] !== null &&  $row['destino'] !== null /*&& $row['stage'] !== null*/ )
        {
            
            //Primero creamos los clientes si ya existen lo tomamos
            $cliente = cliente::select('clientes.*')
            ->where('nombre', $row['cliente'])
            ->first();

            if($cliente == null) //sino existe lo creamos el cliente
            {
               $cliente = cliente::updateOrCreate([
                'nombre' => $row['cliente']
               ]);

               //dd($cliente);
            }

           //Buscamos el destino si existe sino lo creamos
           $destino = destino::select('destinos.*')
           ->where('nombre','=',$row['destino'])
           ->first();
    
            if($destino == null) //sino existe lo creamos el destino
            {
               $destino = destino::updateOrCreate([
                'nombre' => $row['destino']
               ]);
            }


            if($row['stage'] !== null)
            {
              //Buscamos el stage si esxite lo tomamos sino los creamos
              $stage = stage::select('stages.*')
              ->where('nombre', '=' ,$row['stage'])
              ->first();

             // dd($stage);

              if($stage == null) //sino existe lo creamos el stage
              { 
                 $stage = stage::updateOrCreate([
                   'nombre' => $row['stage'],
                   'categoria_stage_id' => 1
                  ]);

                  dt::where('stage_id','=',$stage['id'])
                  ->update(['activo' => 0]);

                  dt::updateOrCreate(
                  [
                     'referencia' => $row['load'],
                  ],
                  [
                  'cliente_id' => $cliente['id'],
                  'stage_id' => $stage['id'],
                  'destino_id' => $destino['id']
                 ]);
              }
              else
              {
                dt::where('stage_id','=',$stage['id'])
                ->update(['activo'=> 0]);
               // dd($cliente['id'].'-' .$stage['id']);
                dt::updateOrCreate(
                 [
                  'referencia' => $row['load']
                 ],
                [
                   'cliente_id' => $cliente['id'],
                   'stage_id' => $stage['id'],
                   'destino_id' => $destino['id']
                ]);
              }
            }
            else
            {    
               dt::updateOrCreate(
               [
                  'referencia' => $row['load'],
               ],
               [
                'cliente_id' => $cliente['id'],
                'destino_id' => $destino['id']
               ]);

            }
    
        }
    }

    /*
    
    public function headingRow(): int
    {
        return 0;
    }
    */
    

    
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
    
    public function isEmptyWhen(array $row): bool
    {
        return $row['load'] == 'LOAD';
    }
    
    
}
