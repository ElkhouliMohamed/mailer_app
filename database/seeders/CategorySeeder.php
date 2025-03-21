<?php
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Pharmacie et Parapharmacie',
            'Technologie et Informatique',
            'Santé et Médical',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
