<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Rules\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;

final class PasswordResetLinkController
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response|ResponseFactory
    {
        return inertia('auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', Rule::email()->default()],
        ]);

        // We will send the password reset link to this admin. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the admin. Finally, we'll send out a proper response.
        $status = Password::broker('admins')->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
