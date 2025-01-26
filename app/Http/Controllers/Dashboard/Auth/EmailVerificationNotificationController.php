<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

final class EmailVerificationNotificationController
{
    /**
     * Send a new email verification notification.
     */
    public function store(): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        if ($admin->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard.welcome', absolute: false));
        }

        $admin->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
