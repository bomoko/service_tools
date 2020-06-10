<?php

namespace AmazeeIO\Health;


use AmazeeIO\Health\Check\CheckInterface;

class CheckDriver
{

    protected $registeredChecks = [];

    protected $applicableChecks = [];

    public function __construct()
    {

    }

    public function runChecks()
    {
        $checkResults = [];
        foreach ($this->applicableChecks as $name => $check) {
            $checkResults[$check->shortName()] = $check->pass();
        }

        return $checkResults;
    }

    public function registerCheck(CheckInterface $check)
    {
        $this->storeRegisteredCheck($check);

        if ($check->appliesInCurrentEnvironment()) {
            $this->queueCheckToRun($check);
        }
    }

    protected function storeRegisteredCheck(CheckInterface $check)
    {
        $this->registeredChecks[$check->shortName()] = $check;
    }

    protected function queueCheckToRun(CheckInterface $check)
    {
        $this->applicableChecks[$check->shortName()] = $check;
    }

}