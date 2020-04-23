<?php
namespace huangweijie\cron\mode;

use huangweijie\cron\Mode;

class Callback extends Mode
{
    public function handle($action = '')
    {
        // TODO: Implement handle() method.
        if (!is_array($action))
            return;

        foreach ($action as $item) {
            if (empty($item) || !is_array($item))
                continue;

            $callback = array_values($item);
            if (!strpos($callback[0], ':') || (isset($callback[1]) && !is_array($callback[1])))
                continue;

            $callback[0] = explode(':', $callback[0]);
            $callback[1] = empty($callback[1])? []: $callback[1];

            $callback = json_encode(array_slice($callback,0, 2));
            shell_exec("nohup php {$this->think} callback:handle {$callback} >/dev/null 2>&1 &");
        }
    }
}