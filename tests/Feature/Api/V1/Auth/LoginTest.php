<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertSame;

it('can\'t user login', function () {
    postJson(route('api.v1.auth.login'), [
        'email' => 'testing@starter-kit.test',
        'password' => '123456',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

it('can user login', function () {
    $user = User::factory()->create();

    $response = postJson(route('api.v1.auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonStructure(['token']);

    assertSame(
        $user->refresh()->api_token,
        hash('sha256', $response->json('token'))
    );
});
