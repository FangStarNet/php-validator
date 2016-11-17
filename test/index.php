<?php

ini_set('date.timezone','Asia/Shanghai');
require_once "vendor/autoload.php";

use Yunhack\PHPValidator\Validator;



$data = $_GET;

Validator::make($data, [
    "tel" => "string|mobile|to_type:scale:4|alias:user_account",
    "age" => "integer_str|between:18,30|to_type:integer",
    "vip_no" => "required_without_all:tel,age|length_between:12,32|regex:/^2016-07-29:[a-zA-Z0-9]*$/"
],[
    "vip_no.regex" => "错误的vip编号格式！"
]);

if (Validator::has_fails()) {
    echo Validator::error_msg();
    exit;
} else {
    echo "参数校验通过(ok)";
}

echo "<br><pre><br>";
var_dump($data);
