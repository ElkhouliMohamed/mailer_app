<?php

namespace App\Mail;

use App\Models\Contact;
use App\Models\SmtpSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $smtpSetting;
    public $subject;
    public $content;

    public function __construct(Contact $contact, SmtpSetting $smtpSetting, $subject, $content)
    {
        $this->contact = $contact;
        $this->smtpSetting = $smtpSetting;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function build()
    {
        return $this->from($this->smtpSetting->sender_email, $this->smtpSetting->sender_name)
            ->subject($this->subject)
            ->view('emails.bulk')
            ->with(['content' => $this->content]);
    }
}
