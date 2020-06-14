<?php

namespace AmazeeIO\Health\Check;

interface CheckInterface
{

    const STATUS_PASS = 'pass';
    const STATUS_FAIL = 'fail';
    const STATUS_WARN = 'warn';

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
     * The status of the check
     *
     * @return Integer
     */
    public function status();

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