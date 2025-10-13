<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    
    public function getUser($email, $username): mixed {
        $sql = "SELECT * FROM users WHERE email = :email AND username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->bindValue(":username", $username, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function findUserByEmail($email): mixed {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function updateUserInfo() {
        
    }

    public function changePassword() {

    }
}
