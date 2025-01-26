<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

final class EmailVerificationPromptController
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(): RedirectResponse|Response|ResponseFactory
    {
        $admin = type(auth()->user())->as(Admin::class);

        return $admin->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard.welcome', absolute: false))
            : inertia('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
