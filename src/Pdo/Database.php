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

    /** TODO переписать методы */
    public function execute($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public function fetchAll($query, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }
}