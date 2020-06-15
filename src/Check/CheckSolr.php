<?php

namespace AmazeeIO\Health\Check;

use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;


class CheckSolr implements CheckInterface
{

    protected $applies = false;
    protected $solrHost;
    protected $solrCore = 'drupal';
    protected $solrUser;
    protected $solrPassword;

    public function __construct($environment = [])
    {
        if(!empty($environment['SOLR_PORT']))
        {
            $this->applies = true;
            $this->solrHost = $environment['SOLR_PORT'] ?: '8983';
            $this->solrHost = $environment['SOLR_HOST'] ?: 'solr';
            $this->solrCore = $environment['SOLR_CORE'] ?: 'drupal';
            $this->solrUser = $environment['SOLR_USER'] ?: 'drupal';
            $this->solrPassword = $environment['SOLR_PASSWORD'] ?: 'drupal';
        }
    }

    public function appliesInCurrentEnvironment()
    {
        return $this->applies;
    }

    public function result()
    {
        $config = array(
          'endpoint' => array(
            'localhost' => array(
              'host' => $this->solrHost,
              'port' => $this->solrPort,
              'path' => '/',
              'core' => $this->solrCore,
            )
          )
        );

        try {
            $client = new Client(
              new Curl(),
              new EventDispatcher(),
              $config
            );

            $ping = $client->createPing();
            $result = $client->ping($ping);

            if(($result->getData())['status'] == 'OK') {
                return true;
            }

            return false;

        } catch (\Exception $exception) {
            return false;
        }
    }


    public function status()
    {
        if(!$this->result()) {
            return self::STATUS_FAIL;
        }

        return self::STATUS_PASS;
    }

    public function description()
    {
        return "This check tests to see if Solr is available";
    }

    public function shortName()
    {
        return "check_solr";
    }
}