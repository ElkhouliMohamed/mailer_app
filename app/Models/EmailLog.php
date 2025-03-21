<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'contact_id', 'smtp_setting_id', 'subject', 'content', 'status'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function smtpSetting()
    {
        return $this->belongsTo(SmtpSetting::class);
    }
}
