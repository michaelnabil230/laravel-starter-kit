<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Response;

final class RegisterController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(RegisterRequest $request, OtpService $otpService): Response
    {
        /** @var User $user */
        $user = User::query()->create($request->validated());

        $otpService->sendOtp($user);

        return response()->noContent();
    }
}
