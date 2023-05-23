<?php

namespace App\Models;

class Users extends Model
{
    static public function getUsers()
    {
        $sql = <<<SQL
SELECT username, chat_id 
FROM users;
SQL;
        return self::getConnection()->fetchAll($sql);
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

    static public function getUser($username)
    {
        $sql = <<<SQL
SELECT * 
FROM users 
       WHERE username = :username;
SQL;
        return self::getConnection()->fetchAll($sql, ['username' => $username]);
    }

    static public function addUser($user, $town)
    {
        $sql = <<<SQL
INSERT INTO weather.users (username, chat_id, town_weather)
    VALUE (:username, :chat_id, :town);
SQL;
        return self::getConnection()->execute($sql,
            [
                'username' => $user['username'],
                'chat_id' =>  $user['chat_id'],
                'town' => $town
            ]);
    }

    static public function updateTown($user, $town)
    {
        $sql = <<<SQL
UPDATE users 
SET town_weather = :town 
    WHERE username = :username
SQL;
        return self::getConnection()->execute($sql,
            [
                'town' => $town,
                'username' => $user['username'],
            ]);
    }
}