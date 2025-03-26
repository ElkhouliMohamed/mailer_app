<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\EmailLog;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContacts = Contact::count();
        $totalCategories = Category::count();
        $totalEmailsSent = EmailLog::where('status', 'sent')->count();
        $trashedContacts = Contact::onlyTrashed()->count();
        $smtpSettingsCount = SmtpSetting::count();

        $contactsByCategory = Category::withCount('contacts')->get();

        return view('dashboard', compact(
            'totalContacts',
            'totalCategories',
            'totalEmailsSent',
            'trashedContacts',
            'smtpSettingsCount',
            'contactsByCategory'
        ));
    }
}
