<?php
namespace huangweijie\cron\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use huangweijie\Cron;

class Handle extends Command
{
    protected $cron;

    public function __construct(Cron $cron)
    {
        parent::__construct();
        $this->cron = $cron;
    }

    protected function configure()
    {
        $this->setName('crontab:run')->setDescription('crontab run');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$this->initEnv())
            return true;

        $tasks = $this->cron->getTasks();

        foreach ($tasks as $task) {
            if (empty($task['mode']))
                continue;

            if (is_array($task['mode'])) {
                foreach ($task['mode'] as $modeName => $action) {

                    if (is_numeric($modeName) || !in_array($modeName, ['command', 'callback']) || empty($action))
                        continue;

                    $this->cron->mode($modeName)->handle($action);
                }
            }
        }
    }

    private function initEnv()
    {
        if (strtoupper(PHP_OS ) !== 'LINUX') {
            $this->output->writeln('[Warning]:Windows is not supported');
            return false;
        }

        return true;
    }
}