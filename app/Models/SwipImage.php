<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SwipImage extends Model
{
    //
    use HasFactory;
    protected $table = 'swipe_images';
    protected $fillable = [
        'img_url',
        'file_path'
    
    ];
}
