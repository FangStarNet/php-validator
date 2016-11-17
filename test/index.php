<?php

ini_set('date.timezone','Asia/Shanghai');

require_once dirname(dirname(dirname(__DIR__))) . "/autoload.php";

use FangStarNet\PHPValidator\Validator;

$data = $_GET;

echo "<pre>";

Validator::make($data, [
    "tel" => "present|json"
]);

if (Validator::has_fails()) {
    echo Validator::error_msg();
    exit;
} else {
    echo "参数校验通过(ok)";
}

echo "<br><br>";
var_dump($data);
