<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'god_id',
        'ability_thumbnail',
        'description',
        'status',
    ];

    protected $casts = [
        'god_id' => 'integer',
        'ability_thumbnail' => 'string',
        'description' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function god()
    {
        return $this->belongsTo(God::class);
    }
}
