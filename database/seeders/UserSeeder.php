<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Tester',
            'email' => 'tester@app.com',
        ]);

        User::factory()->create([
            'name' => 'Michael Nabil',
            'email' => 'michael@app.com',
            'gender' => Gender::MALE,
        ]);
    }
}
