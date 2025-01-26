<?php

declare(strict_types=1);

use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'name',
            'email',
            'phone',
            'phone_country',
            'email_verified_at',
            'fcm_token',
            'gender',
            'photo',
            'last_login_at',
            'birth_date',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});
