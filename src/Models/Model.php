<?php

namespace App\Models;

use App\Pdo\Database;

abstract class Model
{
    public static function getConnection()
    {
        return Database::getInstance();
    }
}