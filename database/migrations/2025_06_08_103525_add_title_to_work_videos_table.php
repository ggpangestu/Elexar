<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_video', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id'); // tambahkan judul setelah id
        });
    }

    public function down(): void
    {
        Schema::table('work_video', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};