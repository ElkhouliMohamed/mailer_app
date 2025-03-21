<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'host', 'port', 'encryption', 'username', 'password', 'sender_name', 'sender_email'
    ];

    protected $hidden = ['password'];
}
