<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = User::query()
            ->when(
                $request->string('search')->value(),
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['name', 'phone'], 'Like', "%$search%")
            )
            ->take(15)
            ->get(['id', 'name', 'phone'])
            ->map(fn (User $user): array => [
                'value' => $user->id,
                'label' => "$user->name - $user->phone",
            ])
            ->all();

        return response()->json($users);
    }
}
