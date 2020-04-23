<?php
namespace huangweijie\cron;

abstract class Mode
{
    protected $app;
    protected $think;

    public function __construct()
    {
        $this->think = $this->app->getRootPath() . 'think';
    }

    public function setApp(App $app)
    {
        $this->app = $app;
        return $this;
    }

    abstract function handle($action = '');
}