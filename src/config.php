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