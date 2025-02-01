<?php

namespace Blaze\Utils\CLI;

use Blaze\Database\Migrations;
use Blaze\Utils\Loggr;

class Bootstrapper
{
    public array $commands;

    public function __construct($_argv)
    {
        $this->commands = include(__DIR__ . "/Commands.php");
        foreach ($this->commands as $command) {
            if (count($_argv) > 1) {
                if ($_argv[1] == $command["name"]) {
                    array_splice($_argv, 0, 2);
                    call_user_func($command["execute"], $_argv);

                    break;
                } elseif (array_key_exists("aliases", $command)) {
                    foreach ($command["aliases"] as $alias) {
                        if ($_argv[1] === $alias) {
                            array_splice($_argv, 0, 2);
                            call_user_func($command["execute"], $_argv);

                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
}
