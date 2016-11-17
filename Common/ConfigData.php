<?php

namespace FangStarNet\PHPValidator\Common;

class ConfigData
{
    private static $config = null;

    private static $baseDir = "";

    public static function getConfig()
    {
        if (self::$config === null) {
            self::init();
        }
        return self::$config;
    }

    private static function init()
    {
        self::$config = require_once dirname(__DIR__) . "/config.php";
    }

    public static function baseDir()
    {
        if (self::$baseDir == "") {
            self::$baseDir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
        }
        return self::$baseDir;
    }
}
