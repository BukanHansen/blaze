<?php

namespace Blaze\Database;

use Blaze\Database\Database;

class Table
{
    protected \PDO $db;
    protected string $query;
    protected string $name;
    protected int $count;

    public function __construct(string $name, array $env)
    {
        $database = new Database($env);

        $this->db = $database->db;
        $this->query = "CREATE TABLE IF NOT EXISTS $name (";
        $this->name = $name;
        $this->count = 0;
    }

    public function varchar(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name VARCHAR($length)";
        else $this->query .= "\n$name VARCHAR($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function text(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name TEXT($length)";
        else $this->query .= "\n$name TEXT($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function char(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name CHAR($length)";
        else $this->query .= "\n$name CHAR($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function bool(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name BOOL($length)";
        else $this->query .= "\n$name BOOL($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function int(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name INT($length)";
        else $this->query .= "\n$name INT($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function bigint(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name INT($length)";
        else $this->query .= "\n$name INT($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function float(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name FLOAT($length)";
        else $this->query .= "\n$name FLOAT($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function double(string $name, int $length = 255, $d)
    {
        if ($this->count > 0) $this->query .= ",\n$,name DOUBLE($length, $d)";
        else $this->query .= "\n$name DOUBLE($length, $d)";
        $this->count += 1;
        return new Column($this);
    }

    public function decimal(string $name, int $length = 255, $d)
    {
        if ($this->count > 0) $this->query .= ",\n$name DEC($length, $d)";
        else $this->query .= "\n$name DEC($length, $d)";
        $this->count += 1;
        return new Column($this);
    }

    public function timestamp(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n,$name TIMESTAMP($length)";
        else $this->query .= "\n$name TIMESTAMP($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function datetime(string $name, int $length = 255)
    {
        if ($this->count > 0) $this->query .= ",\n$name DATETIME($length)";
        else $this->query .= "\n$name DATETIME($length)";
        $this->count += 1;
        return new Column($this);
    }

    public function __updateVariable($var, $value)
    {
        $this->{$var} .= $value;
    }

    public function run()
    {
        $this->query .= "\n);";
        $this->db->prepare($this->query)->execute();
    }

    public function drop()
    {
        $this->db->exec("DROP TABLE IF EXISTS {$this->name};");
    }
}
