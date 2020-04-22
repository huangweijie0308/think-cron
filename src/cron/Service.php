<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22 0022
 * Time: 9:28
 */
namespace huangweijie\cron;

use huangweijie\cron\command\Handel;

class Service extends \think\Service
{

    public function boot()
    {
        $this->commands([
            Handle::class,
        ]);
    }
}