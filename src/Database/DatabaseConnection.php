<?php

namespace App\Database;

use PDO;
use PDOException;
use Exception;

class DatabaseConnection
{
    public static function connect($host, $dbname, $username, $password): PDO
    {
        $dsn = "pgsql:host=$host;dbname=$dbname;port=5432;";

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
            return $pdo;
        } catch (PDOException $e) {
            // Handle connection errors
            throw new Exception("Failed to connect to the database: " . $e->getMessage());
        }
    }
}