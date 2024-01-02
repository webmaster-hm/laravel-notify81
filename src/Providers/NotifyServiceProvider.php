<?php

namespace Tylercd100\Notify\Providers;

use Illuminate\Support\ServiceProvider;
use Tylercd100\Notify\Drivers\FleepHook;
use Tylercd100\Notify\Drivers\Flowdock;
use Tylercd100\Notify\Drivers\FromConfig;
use Tylercd100\Notify\Drivers\Mail;
use Tylercd100\Notify\Drivers\Plivo;
use Tylercd100\Notify\Drivers\Pushover;
use Tylercd100\Notify\Drivers\Slack;
use Tylercd100\Notify\Drivers\Sentry;
use Tylercd100\Notify\Drivers\Twilio;

class NotifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/notify.php', 'notify');

        $registerMap = [
            'notify' => FromConfig::class,
            'notify-fleephook' => FleepHook::class,
            'notify-flowdock' => Flowdock::class,
            'notify-mail' => Mail::class,
            'notify-plivo' => Plivo::class,
            'notify-pushover' => Pushover::class,
            'notify-sentry' => Sentry::class,
            'notify-slack' => Slack::class,
            'notify-twilio' => Twilio::class,
        ];

        $this->registerSingletonsFromMap($registerMap);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/notify.php' => base_path('config/notify.php'),
        ]);
    }

    private function registerSingletonsFromMap($map)
    {
        foreach ($map as $key => $class) {
            $this->app->singleton($key, function () use ($class) {
                return new $class;
            });
        }
    }
}
