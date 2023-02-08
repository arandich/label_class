<?php

namespace Database;

use PDO;

class DbService
{
    private string $dbname = 'tests';

    private string $username = 'postgres';

    private string $password = 'admin';

    private string $host = '127.0.0.1';

    private int $port = 5432;


    function __construct()
    {
        if (isset($GLOBALS['DB_HOST'])) {
            $this->host = $GLOBALS['DB_HOST'];
        }
        if (isset($GLOBALS['DB_USER'])) {
            $this->username = $GLOBALS['DB_USER'];
        }
        if (isset($GLOBALS['DB_PASSWORD'])) {
            $this->password = $GLOBALS['DB_PASSWORD'];
        }
        if (isset($GLOBALS['DB_DBNAME'])) {
            $this->dbname = $GLOBALS['DB_DBNAME'];
        }
        if (isset($GLOBALS['DB_PORT'])) {
            $this->port = $GLOBALS['DB_PORT'];
        }
    }

    public function connect(): PDO
    {
        $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname";
        try {
            return new \PDO($dsn, $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
