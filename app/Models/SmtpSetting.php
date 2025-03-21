<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'user_id', 'host', 'port', 'encryption', 'username', 'password', 'sender_name', 'sender_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $hidden = ['password'];
}
