<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\JsonResponse;

final class UpdateUserController
{
    public function __invoke(UpdateUserRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        $validated = $request->safe()->except('photo');

        if ($request->photo) {
            $validated = array_merge($validated, $user->updateProfilePhoto($request->photo));
        }

        $user->update($validated);

        $user = $user->refresh();

        return response()->json([
            'user' => UserResource::make($user),
        ]);
    }
}
