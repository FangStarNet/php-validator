<?php

namespace FangStarNet\PHPValidator\Common;

class Keys
{
    public $all_keys = [
        'present',

        'alpha', 'num', 'alpha_num', 'alpha_dish', 'var', 'ip', 'url', 'email', 'mobile',
        'json', 'timestamp', 'date_format', 'regex',

        'string', 'boolean', 'integer', 'float', 'array', 'object', 'object_of',

        'integer_str', 'float_str', 'numeric_str', 'array_str',

        'max', 'length_max', 'min', 'length_min', 'between', 'length', 'length_between', 'in', 'not_in',

        'filled', 'distinct', 'different', 'same', 'required_with', 'required_with_all', 'required_without', 'required_without_all',

        'alias', 'to_type',

        'file_exists', 'file_type_in', 'file_max', 'file_min', 'file_size_between'
    ];

    public $kv_keys = [
        'date_format', 'regex', 'object_of', 'max', 'length_max', 'min', 'length_min', 'length',
        'between', 'length_between', 'in', 'not_in', 'different', 'same', 'required_with', 'required_with_all',
        'required_without', 'required_without_all', 'alias', 'file_type_in', 'file_max', 'file_min', 'file_size_between',
        'to_type'
    ];
}
