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
        Schema::create('taskreminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_task_id')->nullable();
            $table->foreign('medical_task_id')->references('id')->on('medicaltasks');
            $table->datetime('reminder_datetime');
            $table->text('reminder_message');
            $table->boolean('reminder_sms')->nullable();
            $table->boolean('reminder_email')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taskreminders');
    }
};
