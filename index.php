<?php

include_once(__DIR__ . "/vendor/autoload.php");

use AmazeeIO\Health\CheckDriver;

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
  $psr17Factory, // ServerRequestFactory
  $psr17Factory, // UriFactory
  $psr17Factory, // UploadedFileFactory
  $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();


$driver = new CheckDriver();

$driver->registerCheck(new \AmazeeIO\Health\Check\CheckMariadbRDS($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckRedis($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckNginx($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckPhp($_SERVER));
$driver->registerCheck(new \AmazeeIO\Health\Check\CheckSolr($_SERVER));

$formatter = new \AmazeeIO\Health\Format\JsonFormat($driver);

$responseBody = $psr17Factory->createStream($formatter->formattedResults());
$response = $psr17Factory->createResponse(200)->withBody($responseBody)
  ->withHeader('Cache-Control','no-store')
  ->withHeader('Vary','User-Agent')
  ->withHeader('Content-Type', $formatter->httpHeaderContentType());

(new \Zend\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
