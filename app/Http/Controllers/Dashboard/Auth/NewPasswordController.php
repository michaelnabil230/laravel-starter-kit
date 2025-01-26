<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Models\Admin;
use App\Rules\Rule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;

final class NewPasswordController
{
    /**
     * Show the password reset page.
     */
    public function create(Request $request): Response|ResponseFactory
    {
        return inertia('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', Rule::email()->default()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the admin's password. If it is successful we
        // will update the password on an actual admin model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin) use ($request): void {
                $admin->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        // If the password was successfully reset, we will redirect the admin back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status === Password::PasswordReset) {
            return to_route('dashboard.login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
