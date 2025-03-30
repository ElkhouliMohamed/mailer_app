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
        // Existing dashboard stats
        $totalContacts = Contact::count();
        $totalCategories = Category::count();
        $totalEmailsSent = EmailLog::where('status', 'sent')->count();
        $trashedContacts = Contact::onlyTrashed()->count();
        $smtpSettingsCount = SmtpSetting::count();
        $contactsByCategory = Category::withCount('contacts')->get();

        // New email log stats
        $totalEmailsFailed = EmailLog::where('status', 'failed')->count();
        $totalEmailsPending = EmailLog::where('status', 'pending')->count();
        $emailStatuses = EmailLog::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'totalContacts',
            'totalCategories',
            'totalEmailsSent',
            'trashedContacts',
            'smtpSettingsCount',
            'contactsByCategory',
            'totalEmailsFailed',
            'totalEmailsPending',
            'emailStatuses'
        ));
    }
}
