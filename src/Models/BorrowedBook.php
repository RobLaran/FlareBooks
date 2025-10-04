<?php

namespace App\Models;

use App\Core\Model;

class BorrowedBook extends Model {

    public function getAllTransactions($sortBy="first_name", $sortDir="ASC") {
            $sql = "SELECT 
                        b.title,
                        b.author,
                        b.ISBN,
                        br.first_name,
                        br.last_name,
                        bb.borrow_date,
                        bb.due_date,
                        bb.borrowed_id
                        CASE 
                            WHEN bb.due_date < CURDATE() THEN 1 
                            ELSE 0 
                        END AS is_overdue
                    FROM borrowed_books bb
                    LEFT JOIN books b ON bb.book_id = b.ISBN
                    LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code";

            $allowedSort = [
                "first_name" => "br.first_name",
                "last_name"  => "br.last_name",
            ];

            $defaultSort = $sortBy;

            $results = $this->fetchAll(
                $sql,
                $allowedSort,
                $sortBy,
                $sortDir,
                $defaultSort
            );

            return $results;
    }

    public function addTransaction($transaction) {
        $bookID = $transaction['book_id'] ?: null;
        $borrowerCode= $transaction['borrower_code'] ?: null;
        $dueDate = $transaction['due_date'] ?: null;

        $sql = "INSERT INTO `borrowed_books`(`book_id`, `borrower_code`, `due_date`) 
        VALUES (:bookID,:borrowerCode,:dueDate)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":bookID", $bookID, \PDO::PARAM_STR);
        $stmt->bindValue(":borrowerCode", $borrowerCode, \PDO::PARAM_STR);
        $stmt->bindValue(":dueDate", $dueDate, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function getPaginatedTransactions($limit, $offset, $sortBy = 'first_name', $sortDir = 'ASC', $search = ''): array {
        return $this->paginate(
            'borrowed_books',
            [
                                "first_name" => "br.first_name",
                                "last_name"  => "br.last_name"
                            ],
            $limit,
            $offset,
            $sortBy,
            $sortDir,
            $search,
            ["b.title", "b.author", "b.ISBN", "br.first_name", "br.last_name"], // searchable columns
            "SELECT 
                        b.title,
                        b.author,
                        b.ISBN,
                        br.first_name,
                        br.last_name,
                        DATE_FORMAT(bb.borrow_date, '%M %d, %Y') AS borrow_date,
                        DATE_FORMAT(bb.due_date, '%M %d, %Y') AS due_date,
                        bb.borrowed_id,
                        CASE 
                            WHEN bb.due_date < CURDATE() THEN 1 
                            ELSE 0 
                        END AS status
                    FROM borrowed_books bb
                    LEFT JOIN books b ON bb.book_id = b.ISBN
                    LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code"
        );
    }

    public function getTotalTransactions($search = ''): mixed {
        return $this->getTotal(
        'borrowed_books',
        $search,
        ["b.title", "b.author", "b.ISBN", "br.first_name", "br.last_name"],// searchable columns
        "SELECT 
                    COUNT(*) AS total
                FROM borrowed_books bb
                LEFT JOIN books b 
                    ON bb.book_id = b.ISBN
                LEFT JOIN borrowers br 
                    ON bb.borrower_code = br.borrower_code"
        );
    }
}