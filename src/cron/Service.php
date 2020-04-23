<?php
namespace huangweijie\cron;

use huangweijie\Cron;
use huangweijie\cron\command\Handle;
use huangweijie\cron\command\CallbackHandle;

class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind(Cron::class);
    }

    public function boot()
    {
        $this->commands([
            Handle::class,
            CallbackHandle::class
        ]);
    }
}