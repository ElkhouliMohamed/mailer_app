<?php
namespace App\Jobs;
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkEmail; // You should create this mail class

class BulkEmailController extends Controller
{
    public function sendBulkEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'selected' => 'required|array|min:1',
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $contacts = Contact::whereIn('id', $request->selected)->get();
        $smtpSetting = SmtpSetting::find($request->smtp_setting_id);

        // If no contacts or SMTP setting found, return an error
        if ($contacts->isEmpty() || !$smtpSetting) {
            return back()->with('error', 'Invalid contacts or SMTP settings.');
        }

        // Sending emails to selected contacts
        foreach ($contacts as $contact) {
            // Send email using the BulkEmail Mailable (you need to create this class)
            Mail::to($contact->email)->send(new BulkEmail($contact, $smtpSetting, $request->subject, $request->content));
        }

        return back()->with('success', 'Emails sent successfully.');
    }

    public function testing(Request $request)
    {
        // Get selected contacts from query parameters
        $selected = $request->query('selected');
        $request->merge(['selected' => $selected]);

        // Validate selected contacts
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:contacts,id',
        ]);

        // Fetch selected contacts
        $contacts = Contact::whereIn('id', $request->input('selected'))->get();

        if ($contacts->isEmpty()) {
            return Redirect::route('contacts.index')->with('error', 'No valid contacts selected.');
        }

        $emailCount = 0;

        // Loop through each contact and send a test email
        foreach ($contacts as $contact) {
            try {
                Mail::raw('Test email body', function ($message) use ($contact) {
                    $message->to($contact->email)
                        ->subject('Test Email')
                        ->from(env('MAIL_FROM_ADDRESS'), 'Test');
                });

                $emailCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send test email to {$contact->email}: " . $e->getMessage());
            }
        }

        // Return success or failure message
        return Redirect::route('contacts.index')->with('success', "Test emails sent successfully to {$emailCount} contacts.");
    }


}
