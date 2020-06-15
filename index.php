<?php

include_once(__DIR__ . "/vendor/autoload.php");

use AmazeeIO\Health\CheckDriver;


$driver = new CheckDriver();

//Let's register the checks we want to run

//TODO: maybe we can make this prettier - passing a list of classnames?

var_dump($_SERVER);

$driver->registerCheck(new \AmazeeIO\Health\Check\CheckMariadbRDS($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckRedis($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckNginx($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckPhp($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckSolr($_SERVER));

$checkPass = $driver->pass();


function setHeaders(\AmazeeIO\Health\Format\FormatInterface $formatter) {
    header('Cache-Control: no-store');
    header('Vary: User-Agent');
    header('Content-Type: ' . $formatter->httpHeaderContentType());
}


setHeaders(new \AmazeeIO\Health\Format\JsonFormat($driver));
echo json_encode(["healthy" => $checkPass]);