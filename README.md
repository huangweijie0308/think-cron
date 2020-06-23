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
项目根目录/config/crontab.php
<?php

return [
    'tasks' => [
        [
            'time' => '10,50 */3 * * *',
            'mode' => [
                'command' => ['test', 'think-queue-manage:handle'],
                'callback' => [
                    ["app\admin\controller\JobTest:test1"],
                    [['app\admin\controller\JobTest','test1']], // 需v2.0.0(包括)以上版本
                    ["app\admin\controller\JobTest:test1", 'huangweijie,huangweijie2'],
                    ["app\admin\controller\JobTest:test1", ['huangweijie,huangweijie2']], // 需v2.0.0(包括)以上版本
                    [['app\admin\controller\JobTest','test1'], ['huangweijie,huangweijie2']] // 需v2.0.0(包括)以上版本
                ]
            ]
        ],
        [
            'time' => '* * * * *',
            'mode' => [
                'command' => ['think-queue-manage:handle']
            ]
        ],
        [
            'time' => '2,9,36 */2 * * *',
            'mode' => [
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
* * * * * php /path/to/think crontab:handle >> /dev/null 2>&1
~~~

