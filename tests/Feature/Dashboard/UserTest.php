<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    actingAsAdmin();
});

describe('users Index', function () {
    it('displays the users index page', function () {
        $users = User::factory(10)->create();

        get(route('dashboard.users.index'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->has('users.data', 10)
                    ->where('users.data.0.id', $users->first()->id)
            );
    });

    it('filters users by search', function () {
        $users = User::factory(10)->create();

        get(route('dashboard.users.index', ['search' => $users->first()->name]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('users.data.0.id', $users->first()->id));
    });
});

test('users create', function () {
get(route('dashboard.users.create'))
->assertOk();
    });

test('users store', function () {
    $user = User::factory()->make();

    post(route('dashboard.users.store'), [
        ...$user->toArray(),
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertRedirectToRoute('dashboard.users.index')
        ->assertStatus(302);

    assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'birth_date' => $user->birth_date,
    ]);
});

test('users edit', function () {
    $user = User::factory()->create();

    get(route('dashboard.users.edit', $user))->assertOk();
});

test('users update', function () {
    $user = User::factory()->create();
    $updatedUser = User::factory()->make();

    put(route('dashboard.users.update', $user), $updatedUser->toArray())
        ->assertRedirectToRoute('dashboard.users.index')
        ->assertStatus(302);

    assertDatabaseHas('users', [
        'name' => $updatedUser->name,
        'email' => $updatedUser->email,
        'phone' => $updatedUser->phone,
        'birth_date' => $updatedUser->birth_date,
    ]);
});

test('users destroy', function () {
    $user = User::factory()->create();

    delete(route('dashboard.users.destroy', $user))
        ->assertRedirectToRoute('dashboard.users.index')
        ->assertStatus(302);

    assertSoftDeleted($user);
});
