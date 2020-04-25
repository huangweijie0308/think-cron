<?php

return [
    'tasks' => [
        [
            'time' => '10,50 */3 * * *',
            'mode' => [
                'command' => ['test', 'think-queue-manage:handle'],
                'callback' => [
                    ["app\admin\controller\JobTest:test1", 'huangweijie,huangweijie2']
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