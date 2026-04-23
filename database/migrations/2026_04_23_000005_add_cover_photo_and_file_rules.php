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
        Schema::table('forms', function (Blueprint $table) {
            $table->string('cover_photo')->nullable()->after('description');
        });

        Schema::table('form_fields', function (Blueprint $table) {
            $table->json('validation_rules')->nullable()->after('is_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn('cover_photo');
        });

        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn('validation_rules');
        });
    }
};
