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
        Schema::create('qr_data', function (Blueprint $table) {
            $table->bigIncrements('qrcode_id');
            
            // Foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            
            $table->text('content')->nullable();
            $table->timestamps(); // created_at, updated_at
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->char('is_protected', 1)->default('N');
            $table->dateTime('end_date')->nullable();
            $table->string('qrcode_key')->unique();
            $table->string('qrcode_password')->nullable();
            $table->string('qrcode_image')->nullable();

            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_data');
    }
};
