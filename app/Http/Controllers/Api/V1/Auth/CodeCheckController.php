<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\CodeCheckRequest;
use App\Models\PasswordResetCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CodeCheckController
{
    public function __invoke(CodeCheckRequest $request): JsonResponse|Response
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

        return response()->noContent();
    }
}
