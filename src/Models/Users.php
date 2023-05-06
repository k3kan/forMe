<?php

namespace App\Models;

use App\Pdo\Database;

class Users extends Model
{
    static public function getUsers()
    {
        $sql = <<<SQL
SELECT username, chat_id FROM users;
SQL;
        return self::getConnection()->execute($sql);
    }

    static public function deleteUser($username)
    {
        $sql = <<<SQL
DELETE 
FROM users 
       WHERE username = :username;
SQL;
        return self::getConnection()->execute($sql, ['username' => $username]);
    }
}