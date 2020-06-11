<?php

namespace AmazeeIO\Health\Check;

use Predis\Client;


class CheckRedis implements CheckInterface
{

    protected $redis_host;

    protected $redis_port;

    public function __construct()
    {
        $this->redis_host = getenv('REDIS_HOST') ?: 'redis';
        $this->redis_port = getenv('REDIS_SERVICE_PORT') ?: 6379;
    }

    public function appliesInCurrentEnvironment()
    {
        return true;
    }

    public function pass()
    {
        try {
            $client = new Client([
              'scheme' => 'tcp',
              'host' => $this->redis_host,
              'port' => $this->redis_port,
            ]);

            $response = $client->executeRaw([
              'PING',
            ]);

            return $response == "PONG";
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function description()
    {
        return "This check tests to see if Redis is available";
    }

    public function shortName()
    {
        return "check_redis";
    }
}