<?php
namespace huangweijie\cron\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class CallbackHandle extends Command
{
    protected function configure()
    {
        $this->setName('callback:handle')
            ->addArgument('callback', Argument::OPTIONAL, 'callback', [])
            ->setDescription('callback handle');
    }

    protected function execute(Input $input, Output $output)
    {
        $callback = $input->getArgument('callback')? []: json_decode($callback, true);
        if (empty($callback) || !is_callable($callback[0]))
            return;

        $this->app->invokeMethod($callback[0], $callback[1]);
    }
}