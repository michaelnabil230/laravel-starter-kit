<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Resources\Dashboard\AuthResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Uri;
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

    /**
     * Defines a callback that returns the relative URL.
     */
    public function urlResolver(): Closure
    {
        return function (HttpRequest $request): string {
            $url = Uri::of($request->url())
                ->withQuery($request->query())
                ->value();

            return rawurldecode($url);
        };
    }
}
