<?php

namespace App\Pdo;

use Exception;
use PDO;
use PDOException;

class Database
{
    private \PDO $pdo;
    private static Database $instance;

    public function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host={$_ENV['MYSQL_HOST']};dbname={$_ENV['MYSQL_DATABASE']}", $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            /** TODO запись в логи */
            echo "Connectiond failed " . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function execute($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo throw new Exception($e->getMessage());
        }
    }
}