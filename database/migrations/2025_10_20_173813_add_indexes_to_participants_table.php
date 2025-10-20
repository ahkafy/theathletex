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
        Schema::table('participants', function (Blueprint $table) {
            // Add indexes for commonly queried columns to improve performance
            $table->index('event_id', 'idx_participants_event_id');
            $table->index('category', 'idx_participants_category');
            $table->index('created_at', 'idx_participants_created_at');
            $table->index(['event_id', 'category'], 'idx_participants_event_category');
            $table->index(['event_id', 'created_at'], 'idx_participants_event_created');
        });

        Schema::table('transactions', function (Blueprint $table) {
            // Add indexes for transaction status queries
            $table->index('participant_id', 'idx_transactions_participant_id');
            $table->index('status', 'idx_transactions_status');
            $table->index(['participant_id', 'status'], 'idx_transactions_participant_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex('idx_participants_event_id');
            $table->dropIndex('idx_participants_category');
            $table->dropIndex('idx_participants_created_at');
            $table->dropIndex('idx_participants_event_category');
            $table->dropIndex('idx_participants_event_created');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_participant_id');
            $table->dropIndex('idx_transactions_status');
            $table->dropIndex('idx_transactions_participant_status');
        });
    }
};
