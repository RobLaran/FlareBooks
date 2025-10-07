<?php

namespace App\Core;

use PDO;

class Model {
    protected $db;

    public function __construct() {
        // Use your Database class to get PDO connection
        $connection = new Database();
        $this->db = $connection->connect();
    }

    public function fetchAll (
        $sql = "", 
        $allowedSort = [], 
        $sortBy = "", 
        $sortDir = "ASC", 
        $defaultSort = ""
        ): array {

        // fallback if invalid sort
        $sortBy = $allowedSort[$sortBy] ?? $defaultSort;

        // whitelist directions
        $sortDir = strtoupper($sortDir) === "DESC" ? "DESC" : "ASC";

        $sortSql = " ORDER BY $sortBy $sortDir";

        $stmt = $this->db->prepare($sql . $sortSql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate(
        string $table,
        array $allowedColumns,
        int $limit,
        int $offset,
        string $sortBy = 'id',
        string $sortDir = 'ASC',
        string $search = '',
        array $searchableColumns = [],
        string $sql = ""
    ): array {
        // Validate sorting
        $sortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'id';
        $sortDir = strtoupper($sortDir) === 'DESC' ? 'DESC' : 'ASC';

        // Base SQL
        if(empty($sql)) {
            $sql = "SELECT * FROM {$table} WHERE 1 ";
        } else {
            $sql = $sql . " WHERE 1 ";
        }

        $params = [];

        // Search filter
        if (!empty($search) && !empty($searchableColumns)) {
            $likeParts = [];
            foreach ($searchableColumns as $col) {
                $likeParts[] = "$col LIKE :search";
            }
            $sql .= " AND (" . implode(" OR ", $likeParts) . ")";
            $params[':search'] = "%" . $search . "%";
        }

        // Sorting + Pagination
        $sql .= " ORDER BY $sortBy $sortDir LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal(
    string $table,
    string $search = '',
    array $searchableColumns = [],
    string $sql = ""
    ): int {
        if(empty($sql)) {
            $sql = "SELECT COUNT(*) as total FROM {$table} WHERE 1 ";
        } else {
            $sql = $sql . " WHERE 1 ";
        }

        $params = [];

        if (!empty($search) && !empty($searchableColumns)) {
            $likeParts = [];
            foreach ($searchableColumns as $col) {
                $likeParts[] = "$col LIKE :search";
            }
            $sql .= " AND (" . implode(" OR ", $likeParts) . ")";
            $params[':search'] = "%" . $search . "%";
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

}
