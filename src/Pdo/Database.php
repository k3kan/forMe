<?php

namespace App\Pdo;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private Connection $connection;
    private static Database $instance;

    public function __construct()
    {
        try {
            $params = [
                'host' => $_ENV['MYSQL_HOST'],
                'driver'   => 'pdo_mysql',
                'user'     => $_ENV['MYSQL_USER'],
                'password' => $_ENV['MYSQL_PASSWORD'],
                'dbname'   => $_ENV['MYSQL_DATABASE'],
            ];
            $this->connection = DriverManager::getConnection($params);
        } catch (\PDOException $e) {
            error_log("Ошибка соединения с бд" . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function createQueryBuilder()
    {
        return $this->connection->createQueryBuilder();
    }
}