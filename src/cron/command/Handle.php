<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/18 0018
 * Time: 14:18
 */

namespace huangweijie\cron\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Handle extends Command
{
    private $think = null;
    private $tasks  = [];

    protected function configure()
    {
        $this->setName('crontab:run')->setDescription('crontab run');
    }

    protected function execute(Input $input, Output $output)
    {

        if (!$this->initEnv())
            return true;

        $tasks = $this->getTasks($this->tasks);
        foreach ($tasks as $task) {
            if (empty($task['command']))
                continue;

            shell_exec("nohup php {$this->think} {$task['command']} >/dev/null 2>&1 &");
        }
    }

    private function initEnv()
    {
        if (strtoupper(PHP_OS ) !== 'LINUX') {
            $this->output->writeln('[Warning]:Windows is not supported');
            return false;
        }

        $this->tasks = $this->app->config->get('crontab.tasks', []);
        $this->think = $this->app->getRootPath() . 'think';
        return true;
    }

    private function getTasks(Array $tasks)
    {
        $timeList = $taskList = [];
        $nowTime = time();
        $lastTime = $nowTime - $nowTime % 60 - 60;

        while ((int)($nowTime - $lastTime) > 59) {
            $lastTime += 60;
            $timeList[] = array(
                'nowTime' => $lastTime,
                'nowNode' => explode(' ', date('i H d m w', $lastTime))
            );
        }

        if (!$timeList || empty($tasks) || !is_array($tasks)) {
            return $taskList;
        }

        foreach ($tasks as &$task) {
            $taskTimeNodes = preg_split('@\s+@', trim($task['time']));
            foreach ($timeList as &$timeBox) {
                foreach ($taskTimeNodes as $ki => &$vi) {
                    $index = &$timeBox['nowNode'][$ki];
                    preg_match_all('@(\d+|\*)(?:-(\d+))?(?:/(\d+))?(,|$)@', $vi, $list, PREG_SET_ORDER);

                    foreach ($list as &$vl) {
                        if (!$vl[2]) {
                            $temp = $index == $vl[1] || $vl[1] === '*';
                        } else if ((int)$vl[1] > (int)$vl[2]) {
                            $temp = (int)$index >= (int)$vl[1] || (int)$index <= (int)$vl[2];
                        } else {
                            $temp = (int)$index >= (int)$vl[1] && (int)$index <= (int)$vl[2];
                        }

                        if ($temp && (!$vl[3] || (int)$index >= (int)$vl[1] && ((int)$index - (int)$vl[1]) % (int)$vl[3] === 0)) {
                            continue 2;
                        }
                    }

                    continue 2;
                }

                if (!empty($temp) && (int)$temp < (int)$timeBox['nowTime']) {
                    $task['time'] = $timeBox['nowTime'];
                    $taskList[] = &$task;
                }
            }
            unset($task);
        }
        return $taskList;
    }
}