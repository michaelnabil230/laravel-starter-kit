<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\Dashboard\AuthResource;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'layouts.dashboard.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var ?\App\Models\Admin $admin */
        $admin = auth()->user();

        return [
            ...parent::share($request),
            'auth' => [
                'admin' => $admin ? AuthResource::make($admin) : null,
                'can' => $admin ? ['owner' => true] : [],
            ],
            'toast' => session('toast'),
        ];
    }
}
