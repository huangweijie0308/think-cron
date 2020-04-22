<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22 0022
 * Time: 9:32
 */

return [
    'tasks' => [
        [
            'time' => '10,50 */3 * * *',
            'command' => 'test'
        ],
        [
            'time' => '22 */8 * * *',
            'command' => 'queue'
        ],
        [
            'time' => '* * * * *',
            'command' => 'queue'
        ],
    ]
];