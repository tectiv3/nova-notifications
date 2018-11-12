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

        Event::listen('Illuminate\Notifications\Events\NotificationSent', function (
            $event
        ) {
            NovaNotification::create([
                'id' => $event->notification->id,
                'type' => get_class($event->notification),
                'notifiable_type' => get_class($event->notifiable),
                'notifiable_id' => $event->notifiable->id ?? '?',
                'channel' => $event->channel,
                'failed' => false,
                'data' => $event->notification->getBody() ?? ''
                // 'user_id' => $creator ? $creator->id : null,
                // 'icon' => $data['icon'],
                // 'body' => $data['body'],
                // 'action_text' => array_get($data, 'action_text'),
                // 'action_url' => array_get($data, 'action_url'),
            ]);
        });

        Event::listen('Illuminate\Notifications\Events\NotificationFailed', function (
            $event
        ) {
            NovaNotification::create([
                'id' => $event->notification->id,
                'type' => get_class($event->notification),
                'notifiable_type' => get_class($event->notifiable),
                'notifiable_id' => $event->notifiable->id ?? '',
                'channel' => $event->channel,
                'failed' => true,
                'data' => $event->notification->getBody() ?? ''
            ]);
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
