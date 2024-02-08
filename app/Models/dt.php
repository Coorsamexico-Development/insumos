<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'referencia',
        'cliente_id',
        'stage_id',
        'destino_id',
        'activo'
    ];
}
