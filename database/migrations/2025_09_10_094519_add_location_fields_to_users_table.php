<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('country_id')->nullable()->after('email');
            $table->unsignedTinyInteger('state_id')->nullable()->after('country_id');
            $table->string('pincode', 10)->nullable()->after('state_id');
            $table->string('phone', 15)->nullable()->after('pincode');
            
            // Add foreign key constraints
            $table->foreign('country_id')->references('country_id')->on('countries')->onDelete('set null');
            $table->foreign('state_id')->references('stateid')->on('states')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropColumn(['country_id', 'state_id', 'pincode', 'phone']);
        });
    }
};