<?php
namespace huangweijie;

use InvalidArgumentException;
use think\helper\Str;
use think\Factory;
use think\App;

class Cron extends Factory
{
    protected $namespace = '\\huangweijie\\cron\\mode\\';
    private $tasks = [];

    public function __construct(App $app)
    {
        parent::__construct($app);

        $tasks = $this->app->config->get('crontab.tasks', []);
        $this->tasks = $this->schedule($tasks);
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    protected function createDriver($name = '')
    {
        $driver = empty($name)? $this->getDefaultDriver(): $name;

        $class = false !== strpos($driver, '\\') ? $driver : $this->namespace . Str::studly($driver);

        /** @var Connector $driver */
        if (class_exists($class)) {
            $driver = $this->app->invokeClass($class);

            return $driver->setApp($this->app);
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * @param null $name
     * @return mixed
     */
    public function mode($name = null)
    {
        return $this->driver($name);
    }

    /**
     * 默认驱动
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'callback';
    }

    /**
     * 获取可执行任务列表
     * @param array $tasks
     * @return array
     */
    public function schedule(Array $tasks)
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

        if (!$timeList || empty($tasks) || !is_array($tasks))
            return $taskList;

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