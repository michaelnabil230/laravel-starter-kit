<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Rules\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Response;

final class PasswordResetLinkController
{
    /**
     * Show the password reset link request page.
     */
    public function create(): Response
    {
        return inertia('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', Rule::email()->default()],
        ]);

        Password::sendResetLink($request->only('email'));

        return back()->with('status', __('A reset link will be sent if the account exists.'));
    }
}
