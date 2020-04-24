<?php
namespace huangweijie\cron\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use huangweijie\Cron;

class Handle extends Command
{
    protected $cron;
    protected $able = true;

    public function __construct(Cron $cron)
    {
        parent::__construct();
        $this->cron = $cron;
    }

    protected function configure()
    {
        $this->setName('crontab:run')->setDescription('crontab run');
    }

    protected function initialize(Input $input, Output $output)
    {
        if (strtoupper(PHP_OS ) == 'LINUX')
            return;

        $this->able = false;
        $this->output->writeln('[Warning]:Windows is not supported');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$this->able)
            return;

        $tasks = $this->cron->getTasks();

        foreach ($tasks as $task) {
            if (empty($task['mode']) || !is_array($task['mode']))
                continue;

            foreach ($task['mode'] as $modeName => $action) {

                if (is_numeric($modeName) || !in_array($modeName, ['command', 'callback']) || empty($action))
                    continue;

                $this->cron->mode($modeName)->handle($action);
            }
        }
    }
}