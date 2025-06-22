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
            Schema::create('photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('folder_id');
                $table->string('path_photo');
                $table->integer('order')->default(0);
                $table->boolean('is_display')->default(false);
                $table->timestamps();

                $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};