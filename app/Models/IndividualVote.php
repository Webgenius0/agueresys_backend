<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'god_role_id',
        'vote',
    ];

    protected $casts = [
        'id' => 'integer',
        'anonymous_user_id' => 'integer',
        'god_role_id' => 'integer',
        'vote' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function godRole()
    {
        return $this->belongsTo(GodRole::class);
    }

    public function anonymousUser()
    {
        return $this->belongsTo(AnonymousUser::class);
    }
}
