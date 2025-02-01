<?php

namespace Blaze\Database;

use Blaze\Database\Table;

class Column
{
    protected Table $table;
    protected string $type;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function id()
    {
        $this->table->__updateVariable("query", " PRIMARY KEY AUTO_INCREMENT");

        return new self($this->table);
    }

    public function nullable() {
        $this->table->__updateVariable("query", " NULLABLE");

        return new self($this->table);
    }

    public function not_null() {
        $this->table->__updateVariable("query", " NOT NULL");

        return new self($this->table);
    }

    public function default(mixed $value) {
        $this->table->__updateVariable("query", " DEFAULT $value");

        return new self($this->table);
    }
}
