<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()->superAdmin()->create([
            'name' => 'Super admin',
            'email' => 'super-admin@app.com',
        ]);
    }
}
