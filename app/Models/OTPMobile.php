<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTPMobile extends Model
{
    use HasFactory;
    //
    protected $table = 'otpmobiles';
    protected $fillable = [
        'user_id',
        'mobile', // Include `mobile` as fillable
        'otp',
        'expires_at'];

    public $timestamps = false; // Since you're manually managing `created_at`
}
