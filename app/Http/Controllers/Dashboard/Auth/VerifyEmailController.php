<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

final class VerifyEmailController
{
    /**
     * Mark the authenticated admin's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        if ($admin->hasVerifiedEmail()) {
            return to_route('dashboard.welcome', ['verified' => 1]);
        }

        $request->fulfill();

        return to_route('dashboard.welcome', ['verified' => 1]);
    }
}