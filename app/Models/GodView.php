<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'god_id',
        'anonymous_user_id',
        'fingerprint',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'god_id' => 'integer',
        'anonymous_user_id' => 'integer',
        'fingerprint' => 'string',
    ];

    public function god()
    {
        return $this->belongsTo(God::class);
    }
}
