<?php
namespace huangweijie\cron;

use think\App;

abstract class Mode
{
    protected $app;
    protected $think;

    public function __construct()
    {

    }

    public function setApp(App $app)
    {
        $this->app = $app;
        $this->think = $this->app->getRootPath() . 'think';
        return $this;
    }

    abstract public function handle($action);
}