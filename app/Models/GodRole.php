<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodRole extends Model
{
    use HasFactory;

    protected $table = 'god_roles';

    protected $fillable = [
        'god_id',
        'role_id',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'god_id' => 'integer',
        'role_id' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function god()
    {
        return $this->belongsTo(God::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    // here i store main page votes
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    // here i store single page votes
    public function individualVotes()
    {
        return $this->hasMany(IndividualVote::class);
    }

    // Count the upvotes for the GodRole
    public function upvotes()
    {
        return $this->votes()->where('vote', 'up');
    }

    // Count the downvotes for the GodRole
    public function downvotes()
    {
        return $this->votes()->where('vote', 'down');
    }
    // Count the upvotes for the GodRole
    public function upvotesIndividual()
    {
        return $this->individualVotes()->where('vote', 'up');
    }

    // Count the downvotes for the GodRole
    public function downvotesIndividual()
    {
        return $this->individualVotes()->where('vote', 'down');
    }
}
