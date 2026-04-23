<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->string('respondent_name');
            $table->string('respondent_email');
            $table->string('respondent_phone')->nullable();
            $table->json('response_data')->nullable(); // { "field_id": value }
            $table->string('payment_status')->default('not_required'); // not_required | pending | complete | failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_responses');
    }
};
