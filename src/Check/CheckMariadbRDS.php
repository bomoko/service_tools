<?php

namespace AmazeeIO\Health\Check;


class CheckMariadbRDS implements CheckInterface
{
    protected $disabled = false;
    protected $db_host = null;
    protected $db_username = null;
    protected $db_password = null;
    protected $db_database = null;


    public function __construct($env = [])
    {
        $this->db_host = !empty($env['MARIADB_HOST']) ? $env['MARIADB_HOST'] : $env['AMAZEEIO_DB_HOST'];
        $this->db_username = !empty($env['MARIADB_USERNAME']) ? $env['MARIADB_USERNAME'] : $env['AMAZEEIO_DB_USERNAME'];
        $this->db_password = !empty($env['MARIADB_PASSWORD']) ? $env['MARIADB_PASSWORD'] : $env['AMAZEEIO_DB_PASSWORD'];
        $this->db_database = !empty($env['MARIADB_DATABASE']) ? $env['MARIADB_DATABASE'] : 'drupal';
    }

    public function appliesInCurrentEnvironment()
    {
        return true;
    }

    public function pass()
    {
        $db = $this->getConnection();
        return $this->testRead($db);
    }

    protected function testRead($conn)
    {
        $stmt = $conn->prepare('SHOW DATABASES');
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function description()
    {
        return "This test will attempt to connect to a database (if configured) and perform a simple read and write";
    }

    public function shortName()
    {
        return 'check_db';
    }


    protected function getConnection()
    {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_database}";
        try
        {
            $pdo = new \PDO($dsn, $this->db_username, $this->db_password);
        } catch (\Exception $exception)
        {
            throw $exception;
        }
        return $pdo;
    }

}