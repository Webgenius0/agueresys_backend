<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class God extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'sub_title',
        'description_title',
        'description',
        'thumbnail',
        'aspect_description',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'sub_title' => 'string',
        'description_title' => 'string',
        'description' => 'string',
        'thumbnail' => 'string',
        'aspect_description' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function abilities()
    {
        return $this->hasMany(Ability::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'god_roles')->withTimestamps();
    }

    // Automatically assign all roles when a God is created
    protected static function boot()
    {
        parent::boot();

        static::created(function ($god) {
            $allRoles = Role::pluck('id'); // Fetch all role IDs
            $god->roles()->attach($allRoles); // Assign all roles
        });
    }
}
