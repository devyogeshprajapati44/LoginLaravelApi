<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    //
    use HasFactory;

    protected $table = 'devices';
    protected $fillable = [
        'device_name'
       
    
    ];
}