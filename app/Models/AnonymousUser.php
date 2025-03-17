<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousUser extends Model
{
    use HasFactory;


    protected $fillable = ['ip_address', 'fingerprint'];

    // public function votes()
    // {
    //     return $this->hasMany(Vote::class, 'ip_user_id');
    // }
}
