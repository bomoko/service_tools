<?php

namespace AmazeeIO\Health\Check;


class CheckRDS implements CheckInterface
{
    private $db_host = null;
    private $db_username = null;
    private $db_password = null;
    private $db_database = null;


    public function __construct($environment = [])
    {

    }

    public function appliesInCurrentEnvironment()
    {
        return true;
    }

    public function pass()
    {
        // TODO: Implement pass() method.
    }

    public function description()
    {
        // TODO: Implement description() method.
    }

    public function shortName()
    {
        // TODO: Implement shortName() method.
    }


}