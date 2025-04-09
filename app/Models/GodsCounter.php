<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodsCounter extends Model
{
    use HasFactory;

    protected $table = 'gods_counters';

    protected $fillable = [
        'anonymous_user_id',
        'god_id',
        'counter_god_id',
        'vote',
    ];

    protected $casts = [
        'anonymous_user_id' => 'integer',
        'god_id' => 'integer',
        'counter_god_id' => 'integer',
        'vote' => 'string', // 'up' or 'down'
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    public function god()
    {
        return $this->belongsTo(God::class, 'god_id');
    }

    public function counterGod()
    {
        return $this->belongsTo(God::class, 'counter_god_id');
    }

    public function anonymousUser()
    {
        return $this->belongsTo(AnonymousUser::class);
    }
}
