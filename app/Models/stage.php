<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stage extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'categoria_stage_id'
    ];
}
