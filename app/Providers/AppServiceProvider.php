<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Admin;
use App\Rules\Rule;
use App\Support\Modal\Modal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Email;
use Illuminate\Validation\Rules\Password;
use Inertia\ResponseFactory;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

final class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

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
        $this->defineGates();
        $this->configureEnvironment();
        $this->configureMacros();
        $this->configureValidationRules();

        JsonResource::withoutWrapping();

        URL::forceHttps();

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
            fn (Admin $admin): bool => $admin->email === 'super-admin@app.com',
        );
    }

    protected function configureEnvironment(): void
    {
        Model::shouldBeStrict(App::isLocal());
        Model::automaticallyEagerLoadRelationships(App::isLocal());
        DB::prohibitDestructiveCommands(App::isProduction());
        RouteServiceProvider::loadCachedRoutesUsing($this->loadCachedRoutes(...));
        Vite::prefetch(concurrency: 3);
    }

    protected function configureMacros(): void
    {
        $this->configureInertiaMacros();
    }

    protected function configureInertiaMacros(): void
    {
        ResponseFactory::macro(
            'modal',
            fn (string $component, array $props = []): Modal => new Modal($component, $props),
        );
    }
}
