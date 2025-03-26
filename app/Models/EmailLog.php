<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contact_id', 'smtp_setting_id', 'subject', 'content', 'status', 'attachment'
    ];

    protected $table = 'email_logs';

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function smtpSetting()
    {
        return $this->belongsTo(SmtpSetting::class);
    }
}
