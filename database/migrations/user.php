<?php

use Blaze\Database\Table;

return [
    "name" => "user",
    "apply" => function (Table $table) {
        $table->int("id", 16)->id();
        $table->varchar("username", 255);
        $table->run();
    },
    "drop" => function (Table $table) {
        // Dropping the table
        $table->drop();
    }
];
