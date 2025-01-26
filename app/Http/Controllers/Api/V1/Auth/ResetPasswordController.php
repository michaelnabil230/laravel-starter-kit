<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class ResetPasswordController
{
    public function __invoke(ResetPasswordRequest $request): Response|JsonResponse
    {
        /** @var ?PasswordResetCode $code */
        $code = PasswordResetCode::query()
            ->where('code', $request->code)
            ->first();

        if (is_null($code)) {
            return response()->json(['message' => 'code_is_not_exists']);
        }

        if ($code->isExpired()) {
            return response()->json(['message' => 'code_is_expire']);
        }

        /** @var User $user */
        $user = User::firstWhere('email', $request->email);

        $user->update($request->only('password'));

        $code->delete();

        return response()->noContent();
    }
}
