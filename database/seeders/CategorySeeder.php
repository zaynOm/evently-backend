<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Technology',
            'Education',
            'Health & Wellness',
            'Business',
            'Entertainment',
            'Sports',
            'Lifestyle',
        ];
        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    }
}
