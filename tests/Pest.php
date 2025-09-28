<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;

pest()
    ->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\LazilyRefreshDatabase::class)
    ->beforeEach(function () {
        Str::createRandomStringsNormally();
        Str::createUuidsNormally();
        Http::preventStrayRequests();
        Sleep::fake();

        $this->freezeTime();
    })
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
