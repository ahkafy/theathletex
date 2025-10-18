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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('phone_verification_code')->nullable()->after('phone_verified_at');
            $table->string('address')->nullable()->after('phone_verification_code');
            $table->string('district')->nullable()->after('address');
            $table->string('thana')->nullable()->after('district');
            $table->string('emergency_phone')->nullable()->after('thana');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('emergency_phone');
            $table->date('dob')->nullable()->after('gender');
            $table->string('nationality')->default('Bangladeshi')->after('dob');
            $table->string('tshirt_size')->nullable()->after('nationality');
            $table->string('profile_photo')->nullable()->after('tshirt_size');
            $table->json('sports_interests')->nullable()->after('profile_photo');
            $table->text('bio')->nullable()->after('sports_interests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'phone_verified_at', 'phone_verification_code',
                'address', 'district', 'thana', 'emergency_phone',
                'gender', 'dob', 'nationality', 'tshirt_size',
                'profile_photo', 'sports_interests', 'bio'
            ]);
        });
    }
};
