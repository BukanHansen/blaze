<?php

use Blaze\Config;
use Blaze\Database\Migrations;
use Blaze\Utils\Loggr;

return [
    [
        "name" => "test",
        "description" => "Test",
        "execute" => function (array $args) {
            Loggr::info("Hello, world!");
        }
    ],
    [
        "name" => "migrate",
        "description" => "Migration",
        "execute" => function (array $args) {
            $env = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
            $env->safeload();

            $type = 1;

            foreach ($args as $key) {
                if (str_starts_with($key, "--help") || str_starts_with($key, "-h")) {
                    echo ("php blade \033[1;32mmigrate\033[1;0m\n\n");
                    echo ("Usage: php blade migrate [options]\n");
                    echo ("  php blade migrate --drop\n");
                    echo ("  php blade migrate --refresh\n\n");
                    echo (" --drop, --down, -d              Drop all migrations\n");
                    echo (" --refresh, --fresh, -rf, -f     Refresh all migration (Drop the Apply it again)\n");

                    die;
                }

                if (str_starts_with($key, "--drop") || str_starts_with($key, "-d") || str_starts_with($key, "--down")) {
                    $type = 2;
                }

                if (str_starts_with($key, "--fresh") || str_starts_with($key, "-f") || str_starts_with($key, "--refresh") || str_starts_with($key, "-rf")) {
                    $type = 3;
                }
            }

            $m = new Migrations($_ENV);
            if ($type == 1) {
                $m->runAllMigrations(true);
            } elseif ($type == 2) {
                $m->dropAllMigrations(true);
            } elseif ($type == 3) {
                $m->dropAllMigrations(true);
                echo "\n";
                $m->runAllMigrations(true);
            }
        }
    ],
    [
        "name" => "dev",
        "description" => "Run a local development server",
        "aliases" => ["serve", "server"],
        "execute" => function (array $args) {
            $port = Config::DEFAULT_PORT;
            $host = Config::DEFAULT_HOST;
            $dir = Config::PUBLIC_DIR;

            foreach ($args as $key) {
                if (str_starts_with($key, "--help") || str_starts_with($key, "-h")) {
                    echo ("php blade \033[1;32mdev\033[1;0m\n\n");
                    echo ("Usage: php blade dev [options]\n");
                    echo ("  php blade dev --port=<port>\n");
                    echo ("  php blade dev --host=<host>\n");
                    echo ("  php blade dev --dir=<dir>\n\n");
                    echo (" --port, -p             The port server is running on\n");
                    echo (" --host, -h             The server host is running on\n");
                    echo (" --dir, -d              Directory used for the server default: public\n");

                    die;
                }

                if (str_starts_with($key, "--port=") || str_starts_with($key, "-p=")) {
                    $port = explode("=", $key)[1];
                }

                if (str_starts_with($key, "--dir=") || str_starts_with($key, "-d=")) {
                    $dir = explode("=", $key)[1];
                }

                if (str_starts_with($key, "--host=") || str_starts_with($key, "-h=")) {
                    $host = explode("=", $key)[1];
                }
            }

            passthru("cd $dir && php -S {$host}:{$port} -t .");
        }
    ]
];
