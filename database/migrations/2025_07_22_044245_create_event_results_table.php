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
        Schema::create('event_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->integer('position')->comment('Placement - 1st, 2nd, 3rd etc.');
            $table->string('bib_number')->nullable()->comment('Bib number');
            $table->string('sx')->nullable()->comment('Sx - Side by side racing identifier');
            $table->string('category')->nullable()->comment('Age/Event category');
            $table->integer('category_position')->nullable()->comment('Position by category');
            $table->integer('laps')->nullable()->comment('Number of laps completed');
            $table->time('finish_time')->nullable()->comment('Total race time');
            $table->string('gap')->nullable()->comment('Gap behind winner');
            $table->decimal('distance', 8, 2)->nullable()->comment('Distance covered in km');
            $table->time('chip_time')->nullable()->comment('Chip time');
            $table->decimal('speed', 6, 2)->nullable()->comment('Average speed in km/h');
            $table->time('best_lap')->nullable()->comment('Best lap time');
            $table->boolean('dnf')->default(false)->comment('Did Not Finish');
            $table->boolean('dsq')->default(false)->comment('Disqualified');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'position']);
            $table->index(['event_id', 'category', 'category_position']);
            $table->unique(['event_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_results');
    }
};
