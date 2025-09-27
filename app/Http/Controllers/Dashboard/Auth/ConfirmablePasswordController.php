<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

final class ConfirmablePasswordController
{
    /**
     * Show the confirm password page.
     */
    public function show(): Response
    {
        return inertia('Auth/ConfirmPassword');
    }

    /**
     * Confirm the admin's password.
     */
    public function store(Request $request): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        throw_unless(auth()->validate([
            'email' => $admin->email,
            'password' => $request->password,
        ]), ValidationException::withMessages([
            'password' => __('auth.password'),
        ]));

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard.welcome', absolute: false));
    }
}
