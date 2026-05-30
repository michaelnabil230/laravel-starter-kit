<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Admin;
use App\Models\User;
use App\Rules\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Email;
use Illuminate\Validation\Rules\Password;
use Inertia\ExceptionResponse;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

final class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->defineGates();
        $this->configureEnvironment();
        $this->configureValidationRules();

        JsonResource::withoutWrapping();

        URL::forceHttps();

        View::share('appearance', request()->cookie('appearance', 'system'));

        Inertia::handleExceptionsUsing(function (ExceptionResponse $response) {
            if (! app()->environment(['local', 'testing']) && request()->routeIs('dashboard.*') && in_array($response->statusCode(), [403, 404, 500, 503])) {
                return $response->render('Error', [
                    'status' => $response->statusCode(),
                ])->withSharedData();
            }
        });

        $this->app->booted(fn () => Number::useLocale(app()->getLocale()));
    }

    protected function configureValidationRules(): void
    {
        Email::defaults(function (): ?Email {
            if (app()->runningUnitTests() || app()->isLocal()) {
                return null;
            }

            return Rule::email()
                ->strict()
                ->validateMxRecord()
                ->preventSpoofing();
        });

        Password::defaults(function (): ?Password {
            if (app()->runningUnitTests() || app()->isLocal()) {
                return null;
            }

            return Password::min(8)->uncompromised();
        });
    }

    protected function defineGates(): void
    {
        Gate::define(
            'viewPulse',
            fn (Admin $admin): bool => $admin->email === 'super-admin@whatsapp.com',
        );
    }

    protected function configureEnvironment(): void
    {
        Model::shouldBeStrict(App::isLocal());
        Model::automaticallyEagerLoadRelationships(App::isLocal());
        Relation::enforceMorphMap([
            'admin' => Admin::class,
            'user' => User::class,
        ]);
        DB::prohibitDestructiveCommands(App::isProduction());
        RouteServiceProvider::loadCachedRoutesUsing($this->loadCachedRoutes(...));
        Vite::prefetch(concurrency: 3);
    }
}
