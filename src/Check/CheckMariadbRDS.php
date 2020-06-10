<?php

namespace AmazeeIO\Health\Check;


class CheckMariadbRDS implements CheckInterface
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
        //Set up PDO
    }

    public function description()
    {
        return "This test will attempt to connect to a database (if configured) and perform a simple read and write";
    }

    public function shortName()
    {
        return 'check_mariadb_rds';
    }




}