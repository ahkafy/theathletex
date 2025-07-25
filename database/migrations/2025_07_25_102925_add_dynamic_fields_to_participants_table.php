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
            $table->json('dynamic_fields')->nullable()->after('emergency_phone');
            $table->unsignedBigInteger('event_category_id')->nullable()->after('event_id');

            $table->foreign('event_category_id')->references('id')->on('event_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['event_category_id']);
            $table->dropColumn(['dynamic_fields', 'event_category_id']);
        });
    }
};
