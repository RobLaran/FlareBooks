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

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getPaginatedGenres($limit, $offset, $sortBy = 'genre', $sortDir = 'ASC', $search = ''): array {
        return $this->paginate(
        'genres',
        ['id','genre'],
        $limit,
        $offset,
        $sortBy,
        $sortDir,
        $search,
        ['genre', 'description'] // searchable columns
    );
    }

    public function getTotalGenres($search = ''): mixed {
        return $this->getTotal(
        'genres',
        $search,
        ['genre', 'description'] // searchable columns
        );
    }

}
