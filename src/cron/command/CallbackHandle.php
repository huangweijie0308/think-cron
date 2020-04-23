<?php
namespace huangweijie\cron\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;

class CallbackHandle extends Command
{
    protected function configure()
    {
        $this->setName('crontab:callback')
            ->addOption('class', null, Option::VALUE_REQUIRED, 'class')
            ->addOption('action', null, Option::VALUE_REQUIRED, 'action')
            ->addOption('argument', null, Option::VALUE_OPTIONAL, 'argument', [])
            ->setDescription('callback handle');
    }

    protected function execute(Input $input, Output $output)
    {
        $calss = $input->getOption('class');

        $action = $input->getOption('action');

        $argument = $input->getOption('argument');

        if (!is_callable([$calss, $action]))
            return;

        $argument = empty($argument)? []: explode(',', $argument);
        $this->app->invokeMethod([$calss, $action], $argument);
    }
}