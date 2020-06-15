<?php

namespace AmazeeIO\Health\Check;

/**
 * Class CheckPhp
 *
 * This returns true for all checks at the moment since for the script to run
 * it will be running on a working PHP instance. Further development can
 * integrate into the infrastructure more deeply.
 *
 * @package AmazeeIO\Health\Check
 */
class CheckPhp implements CheckInterface
{

    public function appliesInCurrentEnvironment()
    {
        return true;
    }

    public function result()
    {
        return true;
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
        return "This check tests to see if PHP is available";
    }

    public function shortName()
    {
        return "check_php";
    }
}