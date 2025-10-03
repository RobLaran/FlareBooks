<?php

namespace App\Models;

use App\Core\Model;

class Borrower extends Model {
    public function addBorrower($borrower) {
        $code = $this->generateBorrowerCode();
        $fname = $borrower['fname'] ?: null;
        $lname = $borrower['lname'] ?: null;
        $email = $borrower['email'] ?: null;
        $phone = $borrower['phone'] ?: null;
        $address = $borrower['address'] ?: null;
        $birth = $borrower['birth'] ?: null;

        $sql = "INSERT INTO `borrowers`(`borrower_code`, `first_name`, `last_name`, `email`, `phone`, `address`, `date_of_birth`) 
        VALUES (:code,:first_name,:last_name,:email,:phone,:address,:date_of_birth)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":code", $code, \PDO::PARAM_STR);
        $stmt->bindValue(":first_name", $fname, \PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $lname, \PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, \PDO::PARAM_STR);
        $stmt->bindValue(":phone", $phone, \PDO::PARAM_STR);
        $stmt->bindValue(":address", $address, \PDO::PARAM_STR);
        $stmt->bindValue(":date_of_birth", $birth, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function updateBorrower($id) {
        
    }

    public function removeBorrower($id) {
        $sql = "DELETE FROM borrowers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function getBorroweById($id): mixed {
        $sql = "SELECT * FROM borrowers WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllBorrowers($sortBy = "borrower_code", $sortDir = "ASC"): array {
        $allowedSort = [
            "borrower_code" => "b.borrower_code",
            "first_name"  => "b.first_name",
            "last_name" => "last_name"
        ];

        $sortBy = $allowedSort[$sortBy] ?? "b.borrower_code";

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

    public function generateBorrowerCode() {
        $year = date('Y'); // e.g. 2025

        // Get latest borrower_code for the current year
        $stmt = $this->db->prepare("SELECT borrower_code 
                                    FROM borrowers 
                                    WHERE borrower_code LIKE :yearPattern 
                                    ORDER BY id DESC 
                                    LIMIT 1");
        $stmt->execute([":yearPattern" => "B{$year}-%"]);
        $lastCode = $stmt->fetchColumn();

        if ($lastCode) {
            // Extract the last number part
            $lastNumber = (int) substr($lastCode, -4);
            $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $newNumber = "0001"; // first borrower of the year
        }

        return "B{$year}-{$newNumber}";
    }


}
