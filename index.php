<?php

include_once(__DIR__ . "/vendor/autoload.php");

use AmazeeIO\Health\CheckDriver;

//Wrap any environment vars we want to pass to our checks

$environment = new \AmazeeIO\Health\EnvironmentCollection($_SERVER);


$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
  $psr17Factory, // ServerRequestFactory
  $psr17Factory, // UriFactory
  $psr17Factory, // UploadedFileFactory
  $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();



$driver = new CheckDriver();

$driver->registerCheck(new \AmazeeIO\Health\Check\CheckMariadb($environment));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckRedis($environment));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckNginx($environment));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckPhp($environment));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckSolr($environment));

$formatter = new \AmazeeIO\Health\Format\JsonFormat($driver);

$responseBody = $psr17Factory->createStream($formatter->formattedResults());
$response = $psr17Factory->createResponse($driver->pass() ? 200 : 500)->withBody($responseBody)
  ->withHeader('Cache-Control','no-store')
  ->withHeader('Vary','User-Agent')
  ->withHeader('Content-Type', $formatter->httpHeaderContentType());

(new \Zend\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
