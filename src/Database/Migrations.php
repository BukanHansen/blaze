<?php

namespace Blaze\Database;

use Blaze\Config;

class Migrations
{
    protected \PDO $db;
    protected array $env;

    public function __construct(array $env)
    {
        $database = new Database($env);

        $this->db = $database->db;
        $this->env = $env;
    }

    public function getMigrations()
    {
        $migrations = scandir(Config::MIGRATION_PATH);
        $migrations = array_filter($migrations, function ($var) {
            return !str_starts_with($var, ".");
        });
        return $migrations;
    }

    public function createMigrationsTable()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT(16) PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255),
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");
    }

    public function runAllMigrations(bool $logger = false)
    {
        $this->createMigrationsTable();
        if ($logger === true) {
            print_r("[\033[1;32mMigration\033[1;0m] - Running all Migrations\n");
        }

        $migrations = $this->getMigrations();
        foreach ($migrations as $m) {
            $migrationArray = include(Config::MIGRATION_PATH . "/$m");
            if (is_array($migrationArray) && $migrationArray["apply"]) {
                if ($logger === true) {
                    print_r("[\033[1;32mMigration\033[1;0m] - Running $m\n");
                }

                call_user_func($migrationArray["apply"], new Table($migrationArray["name"], $this->env));
                $this->db->exec("INSERT INTO migrations (name) VALUES ('$m')");

                if ($logger === true) {
                    print_r("[\033[1;32mMigration\033[1;0m] - Successfully running $m!\n");
                }
            } else {
                echo("[\033[1;31mERROR\033[1;0m] $m Migration does not have 'apply' method!");
            }
        }
    }

    public function dropAllMigrations(bool $logger = false)
    {
        $this->db->exec("DROP TABLE IF EXISTS migrations");

        if ($logger === true) {
            print_r("[\033[1;32mMigration\033[1;0m] - Dropping all Migrations\n");
        }

        $migrations = $this->getMigrations();
        foreach ($migrations as $m) {
            $migrationArray = include(Config::MIGRATION_PATH . "/$m");
            if (is_array($migrationArray) && $migrationArray["drop"]) {
                if ($logger === true) {
                    print_r("[\033[1;32mMigration\033[1;0m] - Dropping $m\n");
                }

                call_user_func($migrationArray["drop"], new Table($migrationArray["name"], $this->env));

                if ($logger === true) {
                    print_r("[\033[1;32mMigration\033[1;0m] - Successfully dropping $m!\n");
                }
            } else {
                echo("[\033[1;31mERROR\033[1;0m] $m Migration does not have 'drop' method!");
            }
        }
    }
}
