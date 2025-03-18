<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function gods()
    {
        return $this->belongsToMany(God::class, 'god_roles')->withTimestamps();
    }

    // Define a relationship with GodRole (a role can be assigned to many gods)
    public function godRoles()
    {
        return $this->hasMany(GodRole::class);
    }
}
