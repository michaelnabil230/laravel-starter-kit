<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Admin\StoreRequest;
use App\Http\Requests\Dashboard\Admin\UpdateRequest;
use App\Http\Resources\Dashboard\AdminResource;
use App\Models\Admin;
use App\Traits\SendToasts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

final class AdminController
{
    use SendToasts;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->value(),
        ];

        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        $admins = Admin::query()
            ->whereNot('id', $admin->id)
            ->when(
                $filters['search'],
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'email'], 'Like', "%$search%")
            )
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->appends($filters);

        return inertia('Admins/Index', [
            'filters' => $filters,
            'admins' => AdminResource::collection($admins),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Admins/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        Admin::query()->create($request->validated());

        return to_route('dashboard.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin): Response
    {
        return inertia('Admins/Edit', ['admin' => AdminResource::make($admin)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Admin $admin): RedirectResponse
    {
        $admin->update($request->validated());

        return to_route('dashboard.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        $admin->delete();

        return to_route('dashboard.admins.index');
    }

    /**
     * Pluck and destroy the specified resources from storage.
     */
    public function pluckDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
        ]);

        Admin::destroy($request->collect('ids'));

        return to_route('dashboard.admins.index');
    }
}
