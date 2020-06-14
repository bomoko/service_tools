<?php

namespace AmazeeIO\Health;


use AmazeeIO\Health\Check\CheckInterface;

class CheckDriver implements CheckDriverInterface
{

    protected $registeredChecks = [];

    protected $applicableChecks = [];

    protected $lastRunResults = null;

    protected $lastRunResultStatuses = null;

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
        $checkStatuses = [];
        foreach ($this->applicableChecks as $name => $check) {
            $checkResults[$check->shortName()] = $check->result();
            $checkStatuses[$check->shortName()] = $check->status();
        }

        $this->lastRunResults = $checkResults;
        $this->lastRunResultStatuses = $checkStatuses;
        $this->hasRun = true;
        return $checkResults;
    }


    public function pass()
    {
        if(!$this->hasRun) {
            $this->runChecks();
        }

        return array_reduce($this->lastRunResults, function ($carry, $element) {
            return $carry && $element;
            }, true);
    }

    public function status()
    {
        if(!$this->hasRun) {
            $this->runChecks();
        }

        foreach ($this->lastRunResultStatuses as $status) {
            switch($status) {
                case(CheckInterface::STATUS_FAIL):
                    return CheckInterface::STATUS_FAIL;
                    break;
                case(CheckInterface::STATUS_WARN):
                    return CheckInterface::STATUS_WARN;
                    break;
            }
        }
        return CheckInterface::STATUS_PASS;
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