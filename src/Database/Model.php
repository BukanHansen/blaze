<?php

namespace Blaze\Database;

use Blaze\Database\Database;

class Model
{

    public $pdo;
    public string $table;

    public function table(string $name)
    {
        $this->table = $name;
        $env = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $env->safeLoad();

        $database = new Database($_ENV);
        $this->pdo = $database->db;
    }

    public function findById($id)
    {
        $table = $this->table;
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = $id");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $table = $this->table;
        $stmt = $this->pdo->prepare("SELECT * FROM $table");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $table = $this->table;
        $stmt = $this->pdo->prepare("SELECT * FROM $table LIMIT 1");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function last()
    {
        $table = $this->table;
        $stmt = $this->pdo->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT 1");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $table = $this->table;
        $query = "INSERT INTO $table (";

        $keys = array_keys($data);
        $count = 0;
        foreach ($keys as $k) {
            if ($count >= count($keys)) $query .= "$k,";
            else $query .= "$k";
        }

        $query .= ") VALUES (";

        $values = array_values($data);
        foreach ($values as $v) {
            if ($count >= count($values)) {
                if (is_string($v)) {
                    $query .= "'$v',";
                } else {
                    $query .= "$v,";
                }
            } else {
                if (is_string($v)) {
                    $query .= "'$v'";
                } else {
                    $query .= "$v";
                }
            }
        }

        $query .= ");";

        echo $query . "<br><br>";
        $this->pdo->prepare($query)->execute();
    }
}
