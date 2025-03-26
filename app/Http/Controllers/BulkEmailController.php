<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SmtpSetting;
use App\Models\EmailLog;
use App\Mail\ContactEmail; // Assuming this exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class BulkEmailController extends Controller
{
    public function sendBulkEmails(Request $request)
    {
        // Debug: Uncomment to verify data
        // dd($request->all());

        // Validate the incoming request
        $request->validate([
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'contact_ids' => 'required|array|min:1',
            'contact_ids.*' => 'exists:contacts,id',
        ]);

        // Fetch SMTP settings
        $smtp = SmtpSetting::findOrFail($request->input('smtp_setting_id'));

        // Configure SMTP dynamically
        config([
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.encryption' => $smtp->encryption,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => $smtp->password,
            'mail.from.address' => $smtp->sender_email,
            'mail.from.name' => $smtp->sender_name,
        ]);

        // Fetch selected contacts
        $contacts = Contact::whereIn('id', $request->input('contact_ids'))->get();

        if ($contacts->isEmpty()) {
            return Redirect::route('contacts.index')->with('error', 'No valid contacts found for the selected IDs.');
        }

        // Prepare attachment if provided
        $filePath = null;
        $fileName = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
        }

        $emailCount = 0;
        $failedCount = 0;

        // Send emails to each contact
        foreach ($contacts as $contact) {
            // Prepare the email
            $email = new ContactEmail($request->subject, $request->content);

            if ($filePath) {
                $email->attach($filePath, [
                    'as' => $fileName,
                    'mime' => $request->file('attachment')->getMimeType(),
                ]);
            }

            // Send email with error handling
            try {
                Mail::to($contact->email)->send($email);
                $status = 'sent';
                $message = "Bulk emails sent successfully to {$contacts->count()} contacts.";
                $emailCount++;
            } catch (\Exception $e) {
                $status = 'failed';
                $message = 'Some emails failed to send. Check email logs for details.';
                $failedCount++;
                Log::error('Bulk email sending failed for contact ID ' . $contact->id . ': ' . $e->getMessage());
            }

            // Log each email
            EmailLog::create([
                'contact_id' => $contact->id,
                'smtp_setting_id' => $smtp->id,
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => $status,
                'attachment' => $fileName,
            ]);
        }

        // Determine final message
        if ($failedCount === 0) {
            return Redirect::route('contacts.index')->with('success', "Bulk emails sent successfully to {$emailCount} contacts.");
        } elseif ($emailCount > 0) {
            return Redirect::route('contacts.index')->with('success', "Sent {$emailCount} emails successfully, but {$failedCount} failed.");
        } else {
            return Redirect::route('contacts.index')->with('error', 'All emails failed to send. Check logs for details.');
        }
    }
}
