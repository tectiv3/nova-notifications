<?php

namespace Christophrumpel\NovaNotifications;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Christophrumpel\NovaNotifications\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-notifications');

        $this->publishes(
            [
                __DIR__ . '/../resources/lang' => resource_path(
                    'lang/vendor/nova-notifications'
                )
            ],
            'nova-notifications-lang'
        );

        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-notifications'));

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-notifications')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
