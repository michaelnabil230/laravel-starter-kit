<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DeviceType;
use App\Enums\Gender;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /** @use RefreshOnCreate<\App\Models\User> */
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
            'phone' => fake()->unique()->numerify('9665########'),
            'email' => fake()->boolean()
                ? fake()->unique()->safeEmail()
                : null,
            'remember_token' => Str::random(10),
            'gender' => $gender,
            'birth_date' => fake()->date(max: now()->subYears(25)->format('Y-m-d')),
            'device_type' => fake()->randomElement(DeviceType::cases()),
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): self
    {
        return $this->afterCreating(function (User $user): void {
            $user->generateOtp();
        });
    }
}
