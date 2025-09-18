<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'category_key')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('category_key')->nullable()->after('category_id');
            });
        }

        // Populate existing categories
        $categories = Category::all();
        foreach ($categories as $category) {
            if (empty($category->category_key)) {
                $category->category_key = Str::slug($category->category_name);
                $category->save();
            }
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->string('category_key')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('category_key');
        });
    }
};
