<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    public function findUserById($id): mixed {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    
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

    public function updateUserInfo($id, $updates) {
        $name = $updates['name'] ?: null;
        $email = $updates['email'] ?: null;
        $image = $updates['image'] ?: null;

        $sql = "UPDATE users 
                SET 
                name = :name,
                email = :email,
                image = :image
                WHERE user_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, \PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->bindValue(":image", $image, \PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword($id, $password) {
        $sql = "UPDATE users 
                SET 
                password = :password
                WHERE user_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":password", $password, \PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}
