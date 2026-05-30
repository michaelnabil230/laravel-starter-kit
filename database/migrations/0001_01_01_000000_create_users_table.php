<?php

declare(strict_types=1);

use App\Enums\Gender;
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
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('otp_code')->nullable();
            $table->string('photo')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('otp_last_sent_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->longText('fcm_token')->nullable();
            $table->longText('api_token')->nullable();
            $table->enum('gender', Gender::values());
            $table->timestamp('last_login_at')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('device_type')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
