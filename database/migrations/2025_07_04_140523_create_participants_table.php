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
        Schema::create('participants', function (Blueprint $table) {
            $table->id()->startingValue(251001); // Starting ID from 251001
            $table->unsignedBigInteger('event_id');
            $table->string('category');
            $table->string('reg_type');
            $table->string('fee');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('tshirt_size')->nullable();
            $table->string('kit_option')->nullable();
            $table->string('terms_agreed')->default('on');
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['pending', 'intiated', 'complete', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
