<?php

/*
 | 这是配置文件
 |------------------------------------------------------------------
 |
 | 如果你工程的verdor目录不进行版本控制，那么我们不建议你修改此配置文件！
 |
 */

return [

    /*
     * 语言类型
     *
     * 默认提供en和zh-CN两种，当然你也可以自己修改语言文件(FangStarPhpValidator.php)来个性化文案
     *
     * 文件默认路径：
     *  resources/lang/zh-CN/FangStarPhpValidator.php (适应laravel项目结构)
     *
     * 如果指定的语言文件不存在，会报错！目的是提醒开发人员完善语言文件
     *
     * 注意：本组件是独立的，因此你在Laravel中修改config/app.php的locale配置，不会影响本组件的语言
     */
    'lang' => 'resources/lang/zh-CN/FangStarPhpValidator.php',

    /*
     * 组件在针对浮点数进行参数校验和变量类型转换时，使用的精度值
     */
    'scale' => 8

];
