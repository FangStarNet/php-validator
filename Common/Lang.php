<?php

namespace FangStarNet\PHPValidator\Common;

class Lang
{
    private static $def_lang = [
        'present' => "The parameter '{parameter-name}' is not found!",
        'alpha' => "The parameter '{parameter-name}' is not a alpha",
        'num' => "The parameter '{parameter-name}' is not a number",
        'alpha_num' => "The parameter '{parameter-name}' is not made of number and alpha",
        'alpha_dish' => "The parameter '{parameter-name}' is not made of number,alpha,transverse line and underline",
        'var' => "The parameter '{parameter-name}' is not a valid variable name",
        'ip' => "The parameter '{parameter-name}' is not a valid IP address",
        'url' => "The parameter '{parameter-name}' is not a valid URL string",
        'email' => "The parameter '{parameter-name}' is not a valid E-mail address",
        'mobile' => "The parameter '{parameter-name}' is not a valid mobile",
        'json' => "The parameter '{parameter-name}' is not a valid Json string",
        'timestamp' => "The parameter '{parameter-name}' is not a valid timestamp",
        'date_format' => "The parameter '{parameter-name}' is not a valid date format",
        'regex' => "The parameter '{parameter-name}' does not conform to the regex expression",
        'string' => "The parameter '{parameter-name}' is not a type of string",
        'boolean' => "The parameter '{parameter-name}' is not a type of boolean",
        'integer' => "The parameter '{parameter-name}' is not a type of integer",
        'float' => "The parameter '{parameter-name}' is not a type of float",
        'array' => "The parameter '{parameter-name}' is not a type of array",
        'object' => "The parameter '{parameter-name}' is not a type of object",
        'object_of' => "The parameter '{parameter-name}' is not a '{bind-value_1}' object",
        'integer_str' => "The parameter '{parameter-name}' is not a valid integer string",
        'float_str' => "The parameter '{parameter-name}' is not a valid float string",
        'numeric_str' => "The parameter '{parameter-name}' is not a valid numeric string",
        'array_str' => "The parameter '{parameter-name}' is not a valid array string",
        'max' => "The parameter '{parameter-name}' value is too big",
        'length_max' => "The parameter '{parameter-name}' length is too long",
        'min' => "The parameter '{parameter-name}' value is too small",
        'length_min' => "The parameter '{parameter-name}' length is too short",
        'length' => "The parameter '{parameter-name}' length not is '{bind-value_1}'",
        'between' => "The parameter '{parameter-name}' value is not between '{bind-value_1}' and '{bind-value_2}'",
        'length_between' => "The parameter '{parameter-name}' length is not between '{bind-value_1}' and '{bind-value_2}'",
        'in' => "The parameter '{parameter-name}' value is not in '{bind-value_1}'",
        'not_in' => "The parameter '{parameter-name}' value is in '{bind-value_1}'",
        'filled' => "When the parameter '{parameter-name}' exists, the value cannot be empty",
        'distinct' => "In the array parameter '{parameter-name}',the same value exists",
        'different' => "The parameter '{parameter-name}' is not different of the parameter '{bind-value_1}'",
        'same' => "The parameter '{parameter-name}' is different of the parameter '{bind-value_1}'",
        'required_with' => "The parameter '{parameter-name}' is empty when the parameter '{bind-value_1}' is not empty",
        'required_with_all' => "The parameter '{parameter-name}' is empty",
        'required_without' => "The parameter '{parameter-name}' is empty when the parameter '{bind-value_1}' is empty",
        'required_without_all' => "The parameter '{parameter-name}' is empty",
        'file_exists' => "The file is not found",
        'file_type_in' => "The file's type is must be in {bind-value_1}",
        'file_max' => "The maximum size of the file is {bind-value_1}M",
        'file_min' => "The minimum size of the file is {bind-value_1}M",
        'file_size_between' => "Size of the file must between {bind-value_1}M and {bind-value_2}M",
    ];

    private static $lang = [];

    public static function lang($rule)
    {
        if (self::$lang == []) {
            self::initLangData();
        }
        return self::$lang[$rule];
    }

    private static function initLangData()
    {
        $config = ConfigData::getConfig();
        if (empty($config['lang'])) {
            self::$lang = self::$def_lang;
            return;
        }

        $lang_file = ConfigData::baseDir() . '/' . $config['lang'];
        if (! file_exists($lang_file)) {
            $lang_file = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $lang_file);
            throw new \Exception("The file '{$lang_file}' was not found");
        }
        $lang_data = require_once $lang_file;
        if (! is_array($lang_data) || empty($lang_data)) {
            throw new \Exception("The file '{$lang_file}' must return a non-empty array");
        }
        self::$lang = $lang_data;
    }
}
