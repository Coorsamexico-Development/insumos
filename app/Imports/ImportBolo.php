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
        //Validamos que las columnas no esten vaxias

        $destino = null;
        $destino = destino::select('destinos.*')
        ->where('destinos.nombre','LIKE','%'.$row['destino'].'%')
        ->first();

        if($destino == null)
        {
          $destino = destino::updateOrCreate([
            'nombre' => $row['destino']
          ]);
        }

        $cliente = null;
        $cliente = cliente::select('clientes.*')
        ->where('clientes.nombre','LIKE','%'.$row['cliente'].'%')
        ->first();

        if($cliente == null)
        {
           $cliente = cliente::updateOrCreate([
            'nombre' => $row['cliente']
           ]);
        }


        if($row['stage'] !== null)
        {
           //buscamos el stage
           $stage = null;

           $stage = stage::select('stages.*')
           ->where('stages.nombre','LIKE','%'.$row['stage'].'%')
           ->first();

           if($stage !== null)
           {
            dt::where('stage_id','=',$stage['id'])
            ->update(['activo'=> 0]);
            
            dt::updateOrCreate(
              [
                 'referencia' => $row['load'],
              ],
              [
               
               'cliente_id' => $cliente['id'],
               'stage_id' => $stage['id'],
               'destino_id' => $destino['id'],
               'activo' => 1
              ]);
           }
           else
           {
            //return dd('llego aqui');
             //se crea el stage
             $stage = stage::create([
               'nombre' => $row['stage'],
               'categoria_stage_id' => 1
             ]);

             dt::where('stage_id','=',$stage['id'])
             ->update(['activo'=> 0]);
             
             dt::updateOrCreate(
               [
                  'referencia' => $row['load'],
               ],
               [
                
                'cliente_id' => $cliente['id'],
                'stage_id' => $stage['id'],
                'destino_id' => $destino['id'],
                'activo' => 1
               ]);
              
           }
        }
        else //sino existe el stage crea solamente el dt
        {     
           dt::updateOrCreate(
            [
               'referencia' => $row['load'],
            ],
            [
             'cliente_id' => $cliente['id'],
             'destino_id' => $destino['id'],
             'activo' => 1
            ]); 
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
    
    /*
    public function isEmptyWhen(array $row): bool
    {
        return $row['load'] == 'LOAD';
    }
    */
    
}
