<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Requests\Dashboard\Profile\UpdatePasswordRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

final class PasswordController
{
    /**
     * Update the admin's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        $admin->update(['password' => $request->password]);

        return back();
    }
}
