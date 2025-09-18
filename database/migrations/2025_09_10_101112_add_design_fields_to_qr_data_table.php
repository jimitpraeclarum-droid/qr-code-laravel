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
        Schema::table('qr_data', function (Blueprint $table) {
            // Design customization fields
            $table->string('bg_color', 7)->default('#ffffff')->after('qrcode_image');
            $table->string('square_color', 7)->default('#000000')->after('bg_color');
            $table->string('pixel_color', 7)->default('#000000')->after('square_color');
            $table->string('pattern_style', 20)->default('classic')->after('pixel_color');
            $table->string('frame_style', 20)->default('none')->after('pattern_style');
            $table->string('logo_path')->nullable()->after('frame_style');
            $table->integer('logo_size')->default(30)->after('logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_data', function (Blueprint $table) {
            $table->dropColumn([
                'bg_color',
                'square_color', 
                'pixel_color',
                'pattern_style',
                'frame_style',
                'logo_path',
                'logo_size'
            ]);
        });
    }
};