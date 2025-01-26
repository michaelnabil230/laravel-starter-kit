<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Mail\SendCodeResetPassword;
use App\Models\PasswordResetCode;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

final class ForgotPasswordController
{
    public function __invoke(ForgotPasswordRequest $request): Response
    {
        $code = PasswordResetCode::createCode($request->email);

        Mail::to($request->email)->send(new SendCodeResetPassword($code));

        return response()->noContent();
    }
}
