<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salidas extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'categorias_producto_id',
        'dt_id',
        'cantidad'
    ];
}
