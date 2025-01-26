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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('phone_country');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('fcm_token')->nullable();
            $table->longText('api_token')->nullable();
            $table->enum('gender', Gender::values());
            $table->string('photo')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->date('birth_date');
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
