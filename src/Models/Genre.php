<?php

namespace App\Models;

use App\Core\Model;

class Genre extends Model {
    public function getBookGenre($genreId): mixed {
        $sql = "SELECT genre FROM genres WHERE id = :genreid LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':genreid', (int)$genreId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() ?: null;
    }

    public function getAllGenres(): array {
        $sql = "SELECT * FROM genres";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function genreExist($name): bool {
        $sql = "SELECT COUNT(*) FROM genres WHERE genre = :name";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function addGenre($genre) {
        $name = $genre['name'] ?: null;
        $description = $genre['description'] ?: null;

        $sql = "INSERT INTO `genres`(`genre`, `description`) 
        VALUES (:name,:description)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, \PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function updateGenre($id, $updates) {
        $name = $updates['name'] ?: null;
        $description = $updates['description'] ?: null;

        $sql = "UPDATE genres 
                SET 
                genre = :name,
                description = :description
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, \PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, \PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function removeGenre($id) {
        $sql = "DELETE FROM genres WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result;
    }

    public function format($results): array|null {
        if($results == null) {
            return $results;
        }

        return array_map(function ($row) {
            return [
                "id" => $row['id'],
                "Name" => $row['genre'],
                "Description" => $row['description'],
                "Status" => $row['status']
            ];
        }, $results);
    }
}
