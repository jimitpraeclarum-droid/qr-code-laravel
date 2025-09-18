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
        Schema::create('districts', function (Blueprint $table) {
               $table->smallInteger('district_id')->unsigned()->autoIncrement();
               $table->string('district_name', 25);
               $table->unsignedTinyInteger('state_id');
               $table->char('state_alpha_code', 3);
               $table->foreign('state_id')->references('stateid')->on('states')->onDelete('cascade');
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
