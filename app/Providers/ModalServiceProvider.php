<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Modal\Modal;
use App\Support\Modal\Redirector;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\PropsResolver;
use Inertia\Response;
use Inertia\ResponseFactory;
use Tighten\Ziggy\BladeRouteGenerator;

final class ModalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResponseFactory::macro('modal', fn (string $component, array|Arrayable $props = []): Modal => new Modal($component, $props));

        Modal::beforeBaseRerender(function (): void {
            if (class_exists(BladeRouteGenerator::class)) {
                BladeRouteGenerator::$generated = false;
            }
        });

        // Prevent double encryption of cookies in sub requests
        Modal::excludeMiddlewareOnBaseUrl(EncryptCookies::class);

        Router::macro('setCurrentRequest', function (Request $request): void {
            // @phpstan-ignore-next-line
            $this->currentRequest = $request;
        });

        // Add a 'toArray' macro to Response for consistent serialization so that
        // any response can be serialized to an array. This is used in the Modal
        // class to pass the modal data as a prop to the base URL.
        Response::macro('toArray', function (): array {
            $request = resolve('request');

            $resolver = new PropsResolver($request, $this->component);
            [$resolvedProps, $resolvedMetadata] = $resolver->resolve($this->sharedProps, $this->props);

            return [
                'component' => $this->component,
                'props' => $resolvedProps,
                'version' => $this->version,
                'url' => Str::start(Str::after($request->fullUrl(), $request->getSchemeAndHttpHost()), '/'),
                'meta' => $resolvedMetadata,
            ];
        });

        $this->app->singleton('inertia_modal_redirector', function (Application $app): Redirector {
            $redirector = new Redirector($app->make(UrlGenerator::class));
            if ($app->bound('session.store')) {
                $redirector->setSession($app->make(Session::class));
            }

            return $redirector;
        });

        $this->app->alias('inertia_modal_redirector', 'redirect');
    }
}
