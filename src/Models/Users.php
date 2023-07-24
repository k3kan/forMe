<?php

namespace App\Models;

class Users extends Model
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    static public function getUsers()
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('users', 'u')
            ->fetchAllAssociative();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    static public function deleteUser($username)
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->delete('users', 'u')
            ->where('username = ?')
            ->setParameter(0, $username)
            ->executeStatement();
    }

    static public function getUser($username)
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('users', 'u')
            ->where('username = ?')
            ->setParameter(0, $username)
            ->fetchAssociative();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    static public function addUser($user, $town)
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->insert('users')
            ->values(array(
                'username' => '?',
                'chat_id' =>  '?',
                'town_weather' => '?',
            ))
            ->setParameter(0, $user['username'])
            ->setParameter(1, $user['chat_id'])
            ->setParameter(2, $town)
            ->executeStatement();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    static public function updateTown($user, $town)
    {
        return self::getConnection()
            ->createQueryBuilder()
            ->update('users', 'u')
            ->set('u.town_weather', '?')
            ->where('u.username = ?')
            ->setParameter(0,  $town)
            ->setParameter(1,  $user['username'])
            ->executeStatement();
    }
}