<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function model(array $row)
    {
        $age = null;

        // Vérifie si l'âge est un nombre valide
        if (!empty($row['age']) && is_numeric(trim($row['age']))) {
            $age = (int) trim($row['age']); // Supprime les espaces et cast en entier
        } else {
            Log::warning("Skipping invalid age: " . json_encode($row['age']));
        }

        return new Contact([
            'category_id'   => $this->categoryId ?? $row['category_id'] ?? null,
            'first_name'    => $row['first_name'],
            'last_name'     => $row['last_name'],
            'email'         => $row['email'],
            'phone'         => $row['phone'] ?? null,
            'address'       => $row['address'] ?? null,
            'city'          => $row['city'] ?? null,
            'country'       => $row['country'] ?? null,
            'interests'     => $row['interests'] ?? null,
            'company'       => $row['company'] ?? null,
            'position'      => $row['position'] ?? null,
            'age'           => $age, // Sera null si l'âge est invalide
            'custom_fields' => $row['custom_fields'] ?? null,
        ]);
    }



    public function rules(): array
    {
        return [
            'category_id' => 'nullable|integer|exists:categories,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0',
            'custom_fields' => 'nullable|json',
        ];
    }

    public function headingRow(): int
    {
        return 1; // The first row contains headers
    }
}
