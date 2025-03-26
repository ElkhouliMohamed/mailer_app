<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Category;
use App\Models\Contact;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->input('category_id');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search'); // Add search input

        $contacts = Contact::query()
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->appends($request->except('page')); // Preserve query params

        return view('contacts.index', compact('contacts', 'categories'));
    }

    public function email(Contact $contact)
    {
        $smtpSettings = SmtpSetting::all();
        return view('contacts.email', compact('contact', 'smtpSettings'));
    }

    // 🔹 Helper Method to Configure SMTP Dynamically
    private function configureSmtp(SmtpSetting $smtp)
    {
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => $smtp->password,
        ]);

        Config::set('mail.from', [
            'address' => $smtp->sender_email,
            'name' => $smtp->sender_name,
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $contact = Contact::findOrFail($request->contact_id);
        $smtp = SmtpSetting::findOrFail($request->smtp_setting_id);

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

        // Prepare the email
        $email = new ContactEmail($request->subject, $request->content);
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $email->attach($filePath, [
                'as' => $fileName,
                'mime' => $file->getMimeType(),
            ]);
        }

        // Send email with error handling
        try {
            Mail::to($contact->email)->send($email);
            $status = 'sent';
            $message = 'Email sent successfully!';
        } catch (\Exception $e) {
            $status = 'failed';
            $message = 'Email failed to send. Please try again.';
            Log::error('Email sending failed: ' . $e->getMessage()); // This line now works
        }

        // Log the email
        \App\Models\EmailLog::create([
            'contact_id' => $contact->id,
            'smtp_setting_id' => $smtp->id,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => $status,
            'attachment' => $request->hasFile('attachment') ? $fileName : null,
        ]);

        return redirect()->route('contacts.index')->with('success', $message);
    }

    public function bulkSend(Request $request)
    {
        $request->validate([
            'selected' => 'required|array|min:1|max:200',
            'selected.*' => 'exists:contacts,id',
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $smtp = SmtpSetting::findOrFail($request->smtp_setting_id);

        // Configure SMTP
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

        $contacts = Contact::whereIn('id', $request->selected)->get();
        $successCount = 0;
        $failedCount = 0;

        foreach ($contacts as $contact) {
            try {
                $email = new ContactEmail($request->subject, $request->content);

                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $email->attach($file->getRealPath(), [
                        'as' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                    ]);
                }

                Mail::to($contact->email)->send($email);
                $status = 'sent';
                $successCount++;
            } catch (\Exception $e) {
                $status = 'failed';
                $failedCount++;
                Log::error('Bulk email sending failed for contact ID ' . $contact->id . ': ' . $e->getMessage());
            }

            \App\Models\EmailLog::create([
                'contact_id' => $contact->id,
                'smtp_setting_id' => $smtp->id,
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => $status,
                'attachment' => $request->hasFile('attachment') ? $file->getClientOriginalName() : null,
            ]);
        }

        return redirect()->back()->with([
            'success' => "Bulk email processing completed. $successCount emails sent successfully." .
                ($failedCount ? " $failedCount failed." : ''),
            'success_count' => $successCount,
            'failed_count' => $failedCount
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete(); // Soft delete to trash
        return redirect()->route('contacts.index')->with('success', 'Contact moved to trash.');
    }

    public function trashed()
    {
        $contacts = Contact::onlyTrashed()->paginate(10);
        return view('contacts.trashed', compact('contacts'));
    }

    public function restore($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return redirect()->route('contacts.trashed')->with('success', 'Contact restored successfully.');
    }

    public function create()
    {
        $categories = Category::all();
        return view('contacts.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', // Changed to required per your view
            'email' => 'required|email|max:255|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:65535', // Text column limit
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'custom_fields' => 'nullable|json',
        ]);

        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }
    public function edit(Contact $contact)
    {
        $categories = Category::all();
        return view('contacts.edit', compact('contact', 'categories'));
    }
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email,' . $contact->id,
            'category_id' => 'nullable|exists:categories,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    public function forceDelete($id)
    {
        $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return redirect()->route('contacts.trashed')->with('success', 'Contact permanently deleted.');
    }

    /**
     * Show the import contacts form
     */
    public function showImportForm()
    {
        return view('contacts.import');
    }

    /**
     * Handle the import of contacts from a file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            Excel::import(new ContactsImport, $request->file('file'));
            return Redirect::route('contacts.index')->with('success', 'Contacts imported successfully!');
        } catch (\Exception $e) {
            return Redirect::route('contacts.import')->with('error', 'Failed to import contacts: ' . $e->getMessage());
        }
    }

    /**
     * Provide a sample CSV file for download
     */
    public function downloadSample()
    {
        $filePath = public_path('samples/sample_contacts.csv');
        if (!file_exists($filePath)) {
            // Create a sample file if it doesn’t exist
            $sampleData = "first_name,last_name,email,phone,company,category_id\nJohn,Doe,john.doe@example.com,1234567890,Acme Corp,1\nJane,Smith,jane.smith@example.com,0987654321,Tech Ltd,2";
            file_put_contents($filePath, $sampleData);
        }
        return response()->download($filePath, 'sample_contacts.csv');
    }
}
