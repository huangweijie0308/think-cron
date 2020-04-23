# think-cron 计划任务

#### 介绍
一个基于thinkphp的通用crontab

#### 安装教程

```shell
composer require huangweijie/think-cron
```

#### 使用说明

配置

```
项目根目录/vendor/huangweijie/think-cron/src/config.php
<?php

return [
    'tasks' => [
        [
            'time' => '10,50 */3 * * *',
            'mode' => [
                'command' => ['test', 'queue-manage:run'],
                'callback' => [
                    ["app\admin\controller\JobTest:test1", 'huangweijie,huangweijie2']
                ]
            ]
        ],
        [
            'time' => '* * * * *',
            'mode' => [
                'command' => ['queue-manage:run'],
                'callback' => [
                    ["app\admin\controller\JobTest:test1", 'huangweijie']
                ]
            ]
        ],
    ]
];
```

在系统的计划任务里添加
~~~
* * * * * php /path/to/think crontab:run >> /dev/null 2>&1
~~~

