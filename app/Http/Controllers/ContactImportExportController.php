<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Imports\ContactsImport;
use App\Exports\ContactsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;

class ContactImportExportController extends Controller
{
    public function index()
    {
        return view('contacts.import-export');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            Excel::import(new ContactsImport, $request->file('file'));
            return Redirect::route('contacts.index')->with('success', 'Contacts imported successfully!');
        } catch (\Exception $e) {
            return Redirect::route('contacts.import-export')->with('error', 'Failed to import contacts: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts_' . now()->format('Y-m-d_H-i-s') . '.csv');
    }

    public function downloadSample()
    {
        $filePath = public_path('samples/sample_contacts.csv');
        if (!file_exists($filePath)) {
            $sampleData = "first_name,last_name,email,phone,company,category_id\nJohn,Doe,john.doe@example.com,1234567890,Acme Corp,1\nJane,Smith,jane.smith@example.com,0987654321,Tech Ltd,2";
            file_put_contents($filePath, $sampleData);
        }
        return response()->download($filePath, 'sample_contacts.csv');
    }
}
