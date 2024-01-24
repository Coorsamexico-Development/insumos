<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(permission::class, 'roles_permissions', 'role_id')
            ->withTimestamps();
    }
}
