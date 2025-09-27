<?php

declare(strict_types=1);

use App\Models\Admin;

test('to array', function (): void {
    $admin = Admin::factory()->create();

    expect($admin->toArray())
        ->toHaveKeys([
            'id',
            'name',
            'email',
            'phone',
            'phone_country',
            'email_verified_at',
            'role',
            'photo',
            'gender',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});
