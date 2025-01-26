<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AdminRole;
use App\Enums\Gender;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
final class AdminFactory extends Factory
{
    /** @use RefreshOnCreate<\App\Models\Admin> */
    use RefreshOnCreate;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(Gender::cases());

        return [
            'name' => fake()->name($gender->name),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'phone_country' => 'US',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(AdminRole::cases()),
            'gender' => $gender,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's role should be superAdmin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => AdminRole::SUPER_ADMIN,
        ]);
    }

    /**
     * Indicate that the model's role should be admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role' => AdminRole::ADMIN,
        ]);
    }
}
