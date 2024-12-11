<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordReset extends Model
{
    use HasFactory;
    const UPDATED_AT = null;

    // public $table = 'password_resets';
    public $timestamps = false;
    // protected $primaryKey  = 'email';
    protected $table = 'password_resets';
     

    protected $fillable = [
        'email', 
        'token',
        'created_at'];
}
