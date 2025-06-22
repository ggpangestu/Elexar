<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_video', function (Blueprint $table) {
            $table->id();
            $table->string('video_path')->nullable(); // path file video
            $table->text('description')->nullable(); // deskripsi video
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_video');
    }
};
