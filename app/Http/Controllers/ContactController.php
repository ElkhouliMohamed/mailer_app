<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Category;
use App\Models\Contact;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->input('category_id');
        $perPage = $request->input('per_page', 10);

        $contacts = Contact::when($selectedCategory, function ($query) use ($selectedCategory) {
            return $query->where('category_id', $selectedCategory);
        })
            ->filter($request->all())
            ->paginate($perPage);

        return view('contacts.index', compact('contacts', 'categories'));
    }

    public function email(Contact $contact)
    {
        $smtpSettings = SmtpSetting::all();
        return view('contacts.email', compact('contact', 'smtpSettings'));
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
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

        Mail::to($contact->email)->send(new ContactEmail($request->subject, $request->content));

        // Log the email (optional, if using email_logs table)
        \App\Models\EmailLog::create([
            'contact_id' => $contact->id,
            'smtp_setting_id' => $smtp->id,
            'subject' => $request->subject,
            'content' => $request->content,
            'status' => 'sent',
        ]);

        return redirect()->route('contacts.index')->with('success', 'Email sent successfully!');
    }

    public function bulkSend(Request $request)
    {
        $request->validate([
            'selected' => 'required|array|max:200', // Limit to 200 emails per send
            'smtp_setting_id' => 'required|exists:smtp_settings,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $contacts = Contact::whereIn('id', $request->selected)->get();
        $smtp = SmtpSetting::findOrFail($request->smtp_setting_id);

        // Configure SMTP
        config([
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.encryption' => $smtp->encryption,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => $smtp->password,
            'mail.from.address' => $smtp->sender_email,
            'mail.from.name' => $smtp->sender_name,
        ]);

        foreach ($contacts as $contact) {
            Mail::to($contact->email)->send(new ContactEmail($request->subject, $request->content));
            \App\Models\EmailLog::create([
                'contact_id' => $contact->id,
                'smtp_setting_id' => $smtp->id,
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => 'sent',
            ]);
        }

        return redirect()->route('contacts.index')->with('success', 'Bulk emails sent successfully!');
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
}
