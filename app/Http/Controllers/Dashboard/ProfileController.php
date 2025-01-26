<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\Profile\DeleteProfileRequest;
use App\Http\Requests\Dashboard\Profile\ProfileUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

final class ProfileController
{
    /**
     * Display the admin's profile form.
     */
    public function edit(): Response|ResponseFactory
    {
        return inertia('Profile/Edit');
    }

    /**
     * Update the admin's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        $validated = $request->safe()->except('photo');

        if ($request->hasFile('photo')) {
            $photo = type($request->file('photo'))->as(UploadedFile::class);
            $validated += $admin->updateProfilePhoto($photo);
        }

        $admin->fill($validated);

        if ($admin->isDirty('email')) {
            $admin->email_verified_at = null;
        }

        $admin->save();

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

    /**
     * Delete the current admin's profile photo.
     */
    public function destroyProfilePhoto(): RedirectResponse
    {
        $admin = type(auth()->user())->as(Admin::class);

        $admin->deleteProfilePhoto();

        return back(303);
    }
}
