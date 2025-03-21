<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Default password
        ]);

        // Seed Categories
        $categories = [
            'Pharmacie et Parapharmacie',
            'Technologie et Informatique',
            'Santé et Médical',
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        // Seed Contacts
        $contacts = [
            [
                'category_id' => 1, // Pharmacie et Parapharmacie
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@example.com',
                'phone' => '0123456789',
                'address' => '123 Rue de Paris',
                'city' => 'Paris',
                'country' => 'France',
                'interests' => 'Médicaments',
                'company' => 'PharmaCorp',
                'position' => 'Pharmacien',
                'age' => 35,
            ],
            [
                'category_id' => 2, // Technologie et Informatique
                'first_name' => 'Marie',
                'last_name' => 'Curie',
                'email' => 'marie.curie@example.com',
                'phone' => '0987654321',
                'address' => '456 Tech Lane',
                'city' => 'Lyon',
                'country' => 'France',
                'interests' => 'Programmation',
                'company' => 'TechBit',
                'position' => 'Développeuse',
                'age' => 28,
            ],
            [
                'category_id' => 3, // Santé et Médical
                'first_name' => 'Pierre',
                'last_name' => 'Martin',
                'email' => 'pierre.martin@example.com',
                'phone' => '0147258369',
                'address' => '789 Santé Blvd',
                'city' => 'Marseille',
                'country' => 'France',
                'interests' => 'Chirurgie',
                'company' => 'MediCare',
                'position' => 'Chirurgien',
                'age' => 45,
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }

        // Seed SMTP Settings
        $smtpSettings = [
            [
                'host' => 'smtp.example.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'testuser@example.com',
                'password' => 'testpassword',
                'sender_name' => 'Test Sender',
                'sender_email' => 'testuser@example.com',
            ],
            [
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'yourgmail@gmail.com',
                'password' => 'yourapppassword',
                'sender_name' => 'Gmail Test',
                'sender_email' => 'yourgmail@gmail.com',
            ],
        ];

        foreach ($smtpSettings as $smtpData) {
            SmtpSetting::create($smtpData);
        }
    }
}
