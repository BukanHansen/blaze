<?php

namespace App\Models;

use Blaze\Database\Model;

class User extends Model
{
    public function __construct()
    {
        $this->table("user");
    }
}
