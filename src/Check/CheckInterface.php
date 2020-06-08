<?php

namespace AmazeeIO\Health\Check;

interface CheckInterface
{

    /**
     * Does the current check apply to the current set of services?
     *
     * @return bool
     */
    public function appliesInCurrentEnvironment();

    /**
     * Does the check pass or fail?
     *
     * @return bool
     */
    public function pass();

    /**
     * Generic description of what the check tests for
     *
     * @return string
     */
    public function description();

    /**
     * Short, machine processable, name of the check
     *
     * @return string
     */
    public function shortName();

}