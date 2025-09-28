<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Http\Resources\Dashboard\UserResource;
use App\Models\User;
use App\Notifications\Welcome;
use App\Traits\SendToasts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

final class UserController
{
    use SendToasts;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->value(),
            'gender' => $request->string('gender')->value(),
        ];

        $users = User::query()
            ->filter($request->fluent())
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->appends($filters);

        return inertia('Users/Index', [
            'filters' => $filters,
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->safe()->except('password_confirmation'));

        $user->notify(new Welcome);

        return to_route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        return inertia('Users/Show', [
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        return inertia('Users/Edit', ['user' => UserResource::make($user)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return to_route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return to_route('dashboard.users.index');
    }

    /**
     * Pluck and destroy the specified resources from storage.
     */
    public function pluckDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
        ]);

        User::destroy($request->collect('ids'));

        return to_route('dashboard.users.index');
    }
}
