<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'superadmin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index', 'User', 'Clubadmin','Showpic','Activity','Match','Coursetype','Ordertype','Focus','Review','Club'],
        'model'      => ['User', 'Clubadmin','Showpic','Activity','Match','Coursetype','Ordertype'],
        'view'       => ['index/index'],
    ],
     'clubadmin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index', 'Member', 'Club','Course','Master','Order','Video'],
        'model'      => ['Member', 'Club','Course','Master','Order','Video'],
        'view'       => ['index/index'],
    ],
     'api'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index', 'Lists','Add','Delete','Update','Focus'],
        'model'      => ['Lists','Add','Delete','Update','Focus'],
        'view'       => ['index/index'],
    ],
    // 其他更多的模块定义
];
