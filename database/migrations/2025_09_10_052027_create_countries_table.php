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

           Schema::create('countries', function (Blueprint $table) {
            $table->tinyInteger('country_id')->unsigned()->autoIncrement();
            $table->string('country_name', 100);
            $table->char('country_alpha_code', 3);
            $table->string('country_phone_code', 5);
            $table->engine = 'InnoDB';
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
