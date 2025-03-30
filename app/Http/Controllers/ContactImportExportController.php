<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Imports\ContactsImport;
use App\Exports\ContactsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class ContactImportExportController extends Controller
{
    /**
     * Display the import/export page with a filtered list of contacts.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $contacts = Contact::filter($request->only('search', 'category_id'))
            ->with('category') // Eager load category for display
            ->paginate(10)
            ->appends($request->except('page'));

        return view('contacts.import-export', compact('contacts'));
    }

    /**
     * Handle the import of contacts from a CSV/Excel file with a selected category.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // Max 10MB
            'category_id' => 'nullable|integer|exists:categories,id', // Validate category_id
        ]);

        try {
            $categoryId = $request->input('category_id');
            $file = $request->file('file');

            Excel::import(new ContactsImport($categoryId), $file);
            return Redirect::route('contacts.index')->with('success', 'Contacts imported successfully!');
        } catch (\Exception $e) {
            Log::error('Contact import failed: ' . $e->getMessage());
            return Redirect::route('contacts.import-export')->with('error', 'Failed to import contacts: ' . $e->getMessage());
        }
    }

    /**
     * Export contacts to an Excel file, optionally filtered by category.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        try {
            $categoryId = $request->input('category_id'); // Get category_id from request
            $export = new ContactsExport($categoryId);
            $fileName = $categoryId
                ? "contacts_category_{$categoryId}_" . now()->format('Y-m-d_H-i-s') . '.xlsx'
                : 'contacts_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download($export, $fileName);
        } catch (\Exception $e) {
            Log::error('Contact export failed: ' . $e->getMessage());
            return Redirect::route('contacts.import-export')->with('error', 'Failed to export contacts: ' . $e->getMessage());
        }
    }

    /**
     * Provide a sample CSV file for download.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSample()
    {
        $directory = public_path('samples');
        $filePath = public_path('samples/sample_contacts.csv');

        // Create the 'samples' directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true); // Creates the directory with proper permissions
        }

        // Generate and write the sample file if it doesn't exist
        if (!file_exists($filePath)) {
            $sampleData = implode(',', array_keys((new Contact())->getFillable())) . "\n" .
                "John,Doe,john.doe@example.com,1234567890,Acme Corp,1,123 Main St,New York,USA,Tech;AI,Developer,30,\"{\"key\":\"value\"}\"\n" .
                "Jane,Smith,jane.smith@example.com,0987654321,Tech Ltd,2,456 Elm St,London,UK,Marketing,Manager,35,\"{\"dept\":\"sales\"}\"";
            file_put_contents($filePath, $sampleData);
        }

        return response()->download($filePath, 'sample_contacts.csv');
    }
}
