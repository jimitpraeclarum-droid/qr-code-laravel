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
        Schema::create('qr_history', function (Blueprint $table) {
            $table->bigIncrements('qrcodecount_id');
            $table->unsignedBigInteger('qrcode_id');
            
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->timestamps(); // created_at, updated_at

            // Define foreign key
            $table->foreign('qrcode_id')->references('qrcode_id')->on('qr_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_history');
    }
};
