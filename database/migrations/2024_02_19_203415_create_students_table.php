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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('enrol_id')->nullable();
            $table->string('indexnumber')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('studentype')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('gender');
            $table->date('dateofbirth')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('state')->nullable();
            $table->string('disability')->nullable();
            $table->string('postcode')->nullable();
            $table->text('address')->nullable();
            $table->string('maritalstutus')->nullable();
            $table->string('entrylevel')->nullable();
            $table->string('session')->nullable();
            $table->string('programme')->nullable();
            $table->string('protype')->nullable();
            $table->string('currentlevel')->nullable();
            $table->date('admitted')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
