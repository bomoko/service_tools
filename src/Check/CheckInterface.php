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
     * The result of the check
     *
     * @return mixed
     */
    public function result();

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