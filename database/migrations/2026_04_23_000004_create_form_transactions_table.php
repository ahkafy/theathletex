<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->foreignId('form_response_id')->constrained('form_responses')->onDelete('cascade');
            $table->string('ssl_tran_id')->nullable()->index(); // our generated tran_id sent to SSLCommerz
            $table->string('bank_tran_id')->nullable();         // returned by SSLCommerz
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending'); // pending | complete | failed | cancelled
            $table->json('raw_payload')->nullable();       // full SSLCommerz callback for audit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_transactions');
    }
};
