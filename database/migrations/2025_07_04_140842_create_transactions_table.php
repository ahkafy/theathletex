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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Unique identifier for the transaction
            $table->decimal('amount', 10, 2); // Amount of the transaction
            $table->string('payment_method'); // e.g., 'credit_card', 'paypal', etc.
            $table->string('status'); // e.g., 'pending', 'completed', 'failed'
            $table->text('description')->nullable(); // Optional description of the transaction
            $table->timestamp('transaction_date')->useCurrent(); // Date and time of the transaction
            $table->string('currency', 3)->default('BDT'); // Currency code,
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
