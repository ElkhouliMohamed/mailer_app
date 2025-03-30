<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
    protected $categoryId;

    // Accept category_id as an optional parameter
    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Return the collection of contacts filtered by category if provided.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Contact::query();

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        // Limit to 200 if no category is specified, otherwise export all matching contacts
        return $this->categoryId ? $query->get() : $query->limit(200)->get();
    }

    /**
     * Define the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return array_keys((new Contact())->getFillable());
    }
}
