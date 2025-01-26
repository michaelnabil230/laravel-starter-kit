<?php

declare(strict_types=1);

it('privacy page returns a successful response')
    ->get('/en/legal/privacy')
    ->assertStatus(200)
    ->assertSee('Privacy Policy');

it('Terms and conditions page returns a successful response')
    ->get('/en/legal/terms-and-conditions')
    ->assertStatus(200)
    ->assertSee('App Terms and Conditions');
