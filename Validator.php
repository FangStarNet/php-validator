<?php

namespace FangStarNet\PHPValidator;

class Validator
{
    /**
     * 参数校验是否不通过
     *
     * @var bool
     */
    private static $has_fails = false;

    /**
     * 校验不通过时提示的文案
     *
     * @var string
     */
    private static $error_msg = "";

    /**
     * 参数校验是否不通过
     *
     * @return  bool
     */
    public static function has_fails()
    {
        return self::$has_fails;
    }

    /**
     * 获取提示的文案
     *
     * @return  string
     */
    public static function error_msg()
    {
        return self::$error_msg;
    }

    /**
     * 定义参数校验规则并进行处理
     *
     * @param   array   $data       参数数组 (外层请使用变量作为参数，而不是方法或final数组)
     * @param   array   $rules      参数校验规则
     * @param   array   $messages   自定义文案
     */
    public static function make(array &$data, array $rules, array $messages = [])
    {
        $validator = new Common\Validator($data, $rules, $messages);
        $validator->dissectRuleStr();

        $validator->check();
        $validator->toType();
        $validator->toAlias();
        self::$has_fails = $validator->has_fails();
        self::$error_msg = $validator->err_msg();

        $data = $validator->data();
    }
}
