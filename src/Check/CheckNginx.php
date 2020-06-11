<?php

namespace AmazeeIO\Health\Check;


class CheckNginx implements CheckInterface
{

    public function appliesInCurrentEnvironment()
    {
        return true;
    }

    public function pass()
    {
        return true;
    }

    public function description()
    {
        return "This check tests to see if Nginx is available";
    }

    public function shortName()
    {
        return "check_nginx";
    }
}