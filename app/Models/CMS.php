<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
    use HasFactory;
    protected $fillable = [
        'page',             // Page enum or string (e.g., home_page)
        'section',          // Section name (e.g., banner, about_us)
        'title',            // Title of the section
        'sub_title',        // Subtitle of the section
        'image',            // Image URL or path
        'background_image', // Background Image URL or path
        'description',      // Main description text
        'sub_description',  // Additional description text
        'button_text',      // Text for any button in the section
        'link_url',         // URL link for the button or other purpose
        'status',            // Status of the section (e.g., active, inactive)
    ];
}
