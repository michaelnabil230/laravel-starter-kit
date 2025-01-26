<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\User;

use function Pest\Laravel\actingAs;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\LazilyRefreshDatabase::class)
    ->in(__DIR__);

function actingAsAdmin(): void
{
    $admin = Admin::factory()->superAdmin()->create();

    actingAs($admin);
}

function actingAsUser(): void
{
    $user = User::factory()->create();

    actingAs($user, 'api');
}
