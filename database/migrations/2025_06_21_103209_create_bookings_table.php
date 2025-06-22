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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('booking_date_time');
            $table->string('category');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'rescheduled', 'cancelled', 'expired'])->default('pending');

            // Reschedule
            $table->uuid('reschedule_token')->nullable();
            $table->dateTime('reschedule_expires_at')->nullable();
            $table->string('reschedule_reason')->nullable();
            $table->enum('user_response_status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->dateTime('user_responded_at')->nullable();

            // Meeting
            $table->text('pending_meeting_link')->nullable();
            $table->text('meeting_link')->nullable();
            $table->string('meeting_link_note')->nullable();

            $table->timestamps();

            // Relasi ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
