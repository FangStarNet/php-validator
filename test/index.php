<?php

ini_set('date.timezone','Asia/Shanghai');

require_once dirname(dirname(dirname(__DIR__))) . "/autoload.php";

use FangStarNet\PHPValidator\Validator;

$data = $_GET;

Validator::make($data, [
    "id" => "present|alpha_num|length:32",
],[
    "id.length" => "id不合法",
]);
if (Validator::has_fails()) {
    echo Validator::error_msg();
} else {
    echo "参数校验已经通过";
}
