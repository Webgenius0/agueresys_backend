<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';



    protected $fillable = [
        'title',
        'system_name',
        'email',
        'contact_number',
        'company_open_hour',
        'copyright_text',
        'logo',
        'favicon',
        'address',
        'description',
    ];
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'system_name' => 'string',
        'email' => 'string',
        'contact_number' => 'string',
        'company_open_hour' => 'string',
        'copyright_text' => 'string',
        'logo' => 'string',
        'favicon' => 'string',
        'address' => 'string',
        'description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
