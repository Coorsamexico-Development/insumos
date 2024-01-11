<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias_producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'categoria_id',
        'producto_id',
        'stock_minimo',
        'tiempo_de_reestock'
    ];

    public function entradas()
    {
        return $this->hasMany(entrada::class,'categorias_producto_id');
    }

    public function salidas()
    {
        return $this->hasMany(salidas::class,'categorias_producto_id');
    }

    public function corte_diario()
    {
        return $this->hasOne(corte_diario_historico::class,'categorias_producto_id');
    }
}
