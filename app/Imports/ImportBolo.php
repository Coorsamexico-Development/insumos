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
        if($row['cliente'] !== null &&  $row['destino'] !== null /*&& $row['stage'] !== null*/ )
        {
            //dd($row);
            //Primero creamos los clientes si ya existen lo tomamos
            $cliente = cliente::select('clientes.*')
            ->where('nombre', $row['cliente'])
            ->first();
            //Buscamos el destino si existe sino lo creamos
            $destino = destino::select('destinos.*')
            ->where('nombre','=',$row['destino'])
            ->first();
            //Buscamos el stage si esxite lo tomamos sino los creamos
            $stage = stage::select('stages.*')
            ->where('nombre', '=' ,$row['stage'])
            ->first();
    
            if($cliente == null) //sino existe lo creamos el cliente
            {
               $cliente = cliente::create([
                'nombre' => $row['cliente']
               ]);
            }
    
            if($destino == null) //sino existe lo creamos el destino
            {
               $destino = destino::create([
                'nombre' => $row['destino']
               ]);
            }
    
            if($stage == null) //sino existe lo creamos el stage
            {
               $stage = stage::create([
                 'nombre' => $row['stage'],
                 'categoria_stage_id' => 1
               ]);
            }
    
            //una vez creados o tomados creamos los dts 
            dt::updateOrCreate([
               'referencia' => $row['load'],
               'cliente_id' => $cliente['id'],
               'stage_id' => $stage['id'],
               'destino_id' => $destino['id']
            ]);
        }
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            'cliente' => [
                'required',
                'string',
            ],
            'stage' => [
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
}
