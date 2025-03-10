<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Exports\UsersExport;
use App\Http\Requests\Dashboard\User\StoreRequest;
use App\Http\Requests\Dashboard\User\UpdateRequest;
use App\Http\Resources\Dashboard\UserResource;
use App\Imports\UsersImport;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\ExportExcel;
use App\Notifications\Welcome;
use App\Traits\SendToasts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Response;
use Inertia\ResponseFactory;
use Maatwebsite\Excel\Facades\Excel;

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
        ];

        $users = User::query()
            ->filter($request->fluent())
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->appends($filters);

        return inertia('users/Index', [
            'filters' => $filters,
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response|ResponseFactory
    {
        return inertia('users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $user = User::create($request->safe()->except('password_confirmation'));

        $user->notify(new Welcome);

        return to_route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response|ResponseFactory
    {
        return inertia('users/Show', [
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response|ResponseFactory
    {
        return inertia('users/Edit', ['user' => UserResource::make($user)]);
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

    /**
     * Search for users from the select input.
     */
    public function ajax(Request $request): JsonResponse
    {
        $users = User::query()
            ->when(
                $request->string('search')->value(),
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'email'], 'Like', "%$search%")
            )
            ->take(15)
            ->get(['id', 'name'])
            ->map(fn (User $user): array => [
                'value' => $user->id,
                'label' => $user->name,
            ])
            ->toArray();

        return response()->json($users);
    }

    /**
     * For imported invitations
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'max:50000', 'file', 'mimes:xlsx,application/excel'],
        ]);

        Excel::queueImport(
            new UsersImport,
            type($request->file('file'))->as(UploadedFile::class)
        );

        return to_route('dashboard.users.index');
    }

    /**
     * Export the specified resource.
     */
    public function export(Request $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();

        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        Excel::queue(
            new UsersExport($request->fluent()),
            $filePath = "export/users-{$uuid}.xlsx",
        )->chain([
            fn () => $admin->notify(new ExportExcel($filePath)),
            fn () => Storage::delete($filePath),
        ]);

        return to_route('dashboard.users.index');
    }
}
