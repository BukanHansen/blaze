<?php

namespace Blaze\Database;

class Database
{
    protected $provider;
    protected $host;
    protected $port;
    protected $dbname;
    protected $dbuser;
    protected $dbpassword;

    public \PDO $db;

    public function __construct(array $env)
    {
        $this->provider = $env["DB_PROVIDER"] ?? "mysql";
        $this->host = $env["DB_HOST"] ?? "localhost";
        $this->port = $env["DB_PORT"] ?? "3306";
        $this->dbname = $env["DB_NAME"] ?? "database";

        $this->dbuser = $env["DB_USER"] ?? "root";
        $this->dbpassword = $env["DB_PASSWORD"] ?? "";

        $this->db = new \PDO(
            $this->provider . ":host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname,
            $this->dbuser,
            $this->dbpassword
        );

        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
