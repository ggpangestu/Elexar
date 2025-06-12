<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_images', function (Blueprint $table) {
            $table->text('about_text')->nullable()->after('about_img');
        });
    }

    public function down(): void
    {
        Schema::table('landing_images', function (Blueprint $table) {
            $table->dropColumn('about_text');
        });
    }
};
