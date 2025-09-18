<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = [
            ['name' => 'File', 'key' => 'file'],
            ['name' => 'Contact', 'key' => 'contact'],
            ['name' => 'Socials', 'key' => 'socials'],
            ['name' => 'App', 'key' => 'app'],
            ['name' => 'Location', 'key' => 'location'],
            ['name' => 'Email', 'key' => 'email'],
            ['name' => 'Multi-URL', 'key' => 'multi-url'],
        ];

        foreach ($categories as $category) {
            if (!Category::where('category_key', $category['key'])->exists()) {
                Category::create([
                    'category_name' => $category['name'],
                    'category_key' => $category['key'],
                    'active' => 1,
                    'created_by' => 1, // Assuming user with ID 1 is admin
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $categoryKeys = [
            'file',
            'contact',
            'socials',
            'app',
            'location',
            'email',
            'multi-url',
        ];

        Category::whereIn('category_key', $categoryKeys)->delete();
    }
};
