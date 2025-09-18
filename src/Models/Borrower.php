<?php

namespace App\Models;

use App\Core\Model;

class Borrower extends Model {
    public function getBorrower($borrower_code): mixed {
        $sql = "SELECT * FROM borrowers WHERE borrower_code = :borrower_code LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':borrower_code', $borrower_code, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllBorrowers($sortBy = "borrower_code", $sortDir = "ASC"): array {
        // whitelist allowed columns for sorting (map to actual table.column)
        $allowedSort = [
            "borrower_code" => "b.borrower_code",
            "first_name"  => "b.first_name",
            "last_name" => "last_name"
        ];

        // fallback if invalid sort
        $sortBy = $allowedSort[$sortBy] ?? "b.borrower_code";

        // whitelist directions
        $sortDir = strtoupper($sortDir) === "DESC" ? "DESC" : "ASC";

        $sql = "
            SELECT 
                b.* 
            FROM borrowers b
            ORDER BY $sortBy $sortDir
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getPaginatedBorrowers($limit, $offset, $sortBy = 'borrower_code', $sortDir = 'ASC', $search = ''): array {
        return $this->paginate(
        'borrowers',
        ['borrower_code','first_name', 'last_name'],
        $limit,
        $offset,
        $sortBy,
        $sortDir,
        $search,
        ['borrower_code', 'first_name', 'last_name', 'email', 'phone', 'address', 'date_of_birth', 'membership_date'] // searchable columns
    );
    }

    public function getTotalBorrowers($search = ''): mixed {
        return $this->getTotal(
        'borrowers',
        $search,
        ['borrower_code', 'first_name', 'last_name', 'email', 'phone', 'address', 'date_of_birth', 'membership_date'] // searchable columns
        );
    }

}
