<?php
namespace huangweijie\cron\mode;

class Command extends \huangweijie\cron\Mode
{
    public function handle($action)
    {
        // TODO: Implement handle() method.
        if (is_string($action)) {
            shell_exec("nohup php {$this->think} {$action} >/dev/null 2>&1 &");
        }

        if (is_array($action)) {
            foreach ($action as $commandLine) {
                if (empty($commandLine) || !is_string($commandLine))
                    continue;

                shell_exec("nohup php {$this->think} {$commandLine} >/dev/null 2>&1 &");
            }
        }
    }
}