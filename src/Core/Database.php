<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host = DB_HOST;     
    private $db_name = DB_NAME;  
    private $username = DB_USER;     
    private $password = DB_PASS;         
    private $pdo;

    public function connect() {
        // If connection already exists, return it
        if ($this->pdo) {
            return $this->pdo;
        }

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            // Set error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
