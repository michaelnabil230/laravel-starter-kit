<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;

final class ConfirmablePasswordController
{
    /**
     * Show the confirm password view.
     */
    public function show(): Response|ResponseFactory
    {
        return inertia('Auth/ConfirmPassword');
    }

    /**
     * Confirm the admin's password.
     */
    public function store(Request $request): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        if (! auth()->validate([
            'email' => $admin->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard.welcome', absolute: false));
    }
}
