<?php

include_once(__DIR__ . "/vendor/autoload.php");

use AmazeeIO\Health\CheckDriver;


$driver = new CheckDriver();

//Let's register the checks we want to run

//TODO: maybe we can make this prettier - passing a list of classnames?

$driver->registerCheck(new \AmazeeIO\Health\Check\CheckMariadbRDS($_SERVER));

$checkPass = $driver->pass();

if(!$checkPass) {
//    http_response_code(500);
}


header('Content-Type: application/json');
echo json_encode(["healthy" => $checkPass]);