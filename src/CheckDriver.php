<?php

namespace AmazeeIO\Health;


use AmazeeIO\Health\Check\CheckInterface;

class CheckDriver
{

    protected $registeredChecks = [];

    protected $applicableChecks = [];

    protected $lastRunResults = null;

    protected $hasRun = false;

    public function __construct()
    {

    }

    public function runChecks()
    {
        if(count($this->applicableChecks) == 0)
        {
            throw new NoApplicableCheckException("There were no applicable checks that could be run in this environment");
        }

        $checkResults = [];
        foreach ($this->applicableChecks as $name => $check) {
            $checkResults[$check->shortName()] = $check->pass();
        }

        $this->lastRunResults = $checkResults;
        $this->hasRun = true;
        return $checkResults;
    }


    public function pass()
    {
        if(!$this->hasRun) {
            $this->runChecks();
        }

        return array_reduce($this->lastRunResults, function ($initial, $element) {
            return $initial && $element;
        }, true);
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