<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Http\Resources\Dashboard\UserResource;
use App\Models\User;
use App\Traits\SendToasts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

final class UserController
{
    use SendToasts;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response|ResponseFactory
    {
        $filters = [
            'search' => $request->string('search')->value(),
            'per_page' => $perPage = $request->integer('per_page', 15),
        ];

        $users = User::query()
            ->when(
                $filters['search'],
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'email'], 'Like', "%$search%")
            )
            ->latest()
            ->paginate($perPage)
            ->appends($filters);

        return inertia('Users/Index', [
            'filters' => $filters,
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response|ResponseFactory
    {
        return inertia('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return to_route('dashboard.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response|ResponseFactory
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
