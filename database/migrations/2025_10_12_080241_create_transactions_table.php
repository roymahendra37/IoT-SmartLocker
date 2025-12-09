<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locker_id')->constrained('lockers')->onDelete('cascade');
            $table->enum('type', ['titip', 'kirim'])->default('titip');
            $table->string('name', 100);
            $table->string('email', 100)->nullable();
            $table->string('receiver_name', 100)->nullable();
            $table->string('receiver_email', 100)->nullable();
            $table->integer('duration');
            $table->string('qr_code', 255)->unique();
            $table->string('qr_image', 255)->nullable();
            $table->integer('qr_usage_count')->default(0);
            $table->enum('status', ['active', 'completed','expired'])->default('active');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
            $table->text('fcm_token')->nullable();
            $table->boolean('notified')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};