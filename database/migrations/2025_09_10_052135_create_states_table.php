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
        Schema::create('states', function (Blueprint $table) {
               $table->tinyInteger('stateid')->unsigned()->autoIncrement();
               $table->string('state_name', 100);
               $table->unsignedTinyInteger('country_id');
               $table->char('state_alpha_code', 3);
               $table->foreign('country_id')->references('country_id')->on('countries')->onDelete('cascade');
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
