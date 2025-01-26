<?php

declare(strict_types=1);

use function Pest\Laravel\get;

it('home page returns a successful response')
    ->get('/en')
    ->assertStatus(200);

it('home page has a login button')
    ->get('/en')
    ->assertStatus(200)
    ->assertSee('Login');

it('home page has a dashboard button', function () {
    actingAsAdmin();

    get('/en')->assertOk()->assertSee('Dashboard');
});
