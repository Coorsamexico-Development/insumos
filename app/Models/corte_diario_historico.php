<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class corte_diario_historico extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorias_producto_id',
        'fecha',
        'cantidad_inicial',
        'activo'
    ];
}
