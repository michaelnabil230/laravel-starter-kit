<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Profile\DeleteProfileRequest;
use App\Http\Requests\Dashboard\Profile\ProfileUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

final class ProfileController
{
    /**
     * Display the admin's profile form.
     */
    public function edit(): Response
    {
        return inertia('Profile/Edit');
    }

    /**
     * Update the admin's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        $validatedData = $request->validated();
        if ($admin->isDirty('email')) {
            $validatedData['email_verified_at'] = null;
        }

        $admin->update($validatedData);

        return to_route('dashboard.profile.edit');
    }

    /**
     * Delete the admin's account.
     */
    public function destroy(DeleteProfileRequest $request): HttpFoundationResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        auth()->logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('welcome'));
    }
}
