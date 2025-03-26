<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the email logs.
     */
    public function index(Request $request)
    {
        $query = EmailLog::with('contact');

        // Show trashed records if requested
        if ($request->has('trashed') && $request->trashed === 'true') {
            $query->onlyTrashed();
        }

        // Search filter
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhereHas('contact', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $perPage = $request->per_page ?? 10;
        $emailLogs = $query->latest()->paginate($perPage);

        return view('email_logs.index', compact('emailLogs'));
    }

    /**
     * Display the specified email log.
     */
    public function show(EmailLog $emailLog)
    {
        // Load relationships
        $emailLog->load('contact', 'smtpSetting');

        return view('email_logs.show', compact('emailLog'));
    }

    /**
     * Soft delete the specified email log.
     */
    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();
        return redirect()->route('email-logs.index')->with('success', 'Email log moved to trash.');
    }

    /**
     * Restore a soft-deleted email log.
     */
    public function restore($id)
    {
        $emailLog = EmailLog::onlyTrashed()->findOrFail($id);
        $emailLog->restore();
        return redirect()->route('email-logs.index')->with('success', 'Email log restored.');
    }
}
