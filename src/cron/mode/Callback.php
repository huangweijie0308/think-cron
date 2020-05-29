<?php
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: huangweijie <1539369355@qq.com>
// +----------------------------------------------------------------------
namespace huangweijie\cron\mode;

class Callback extends \huangweijie\cron\Mode
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
            if (!strpos($callback[0], ':') || (isset($callback[1]) && !is_string($callback[1])))
                continue;

            $callback[0] = explode(':', $callback[0]);

            $command = "--class='{$callback[0][0]}' --action='{$callback[0][1]}'";
            if (!empty($callback[1]))
                $command .= " --argument='{$callback[1]}'";

            shell_exec("nohup php {$this->think} crontab:callback {$command} >/dev/null 2>&1 &");
        }
    }
}