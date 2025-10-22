<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class BorrowedBook extends Model {

    public function getHistoriesById($id) {
        $sql = "SELECT 
                    b.title AS book_title,
                    DATE_FORMAT(rb.borrow_date, '%M %d, %Y') AS borrow_date,
                    DATE_FORMAT(rb.return_date, '%M %d, %Y') AS return_date
                FROM returned_books rb
                    LEFT JOIN books b ON rb.book_id = b.ISBN
                WHERE rb.borrower_code = :id 
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionById($id) {
        $sql = "SELECT 
                        b.image,
                        b.title,
                        b.author,
                        b.ISBN,
                        br.first_name,
                        br.last_name,
                        br.borrower_code,
                        bb.borrow_date,
                        bb.due_date,
                        bb.borrowed_id,
                        CASE 
                            WHEN bb.due_date < CURDATE() THEN 1 
                            ELSE 0 
                        END AS is_overdue
                    FROM borrowed_books bb
                        LEFT JOIN books b ON bb.book_id = b.ISBN
                        LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code
                    WHERE bb.borrowed_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllTransactions($sortBy="first_name", $sortDir="ASC") {
        $sql = "SELECT 
                    b.image AS image,
                    b.title AS title,
                    b.author AS author,
                    b.ISBN AS ISBN,
                    br.first_name AS fname,
                    br.last_name AS lname,
                    bb.borrow_date AS borrow_date,
                    bb.due_date AS due_date,
                    bb.borrowed_id AS borrowed_id,
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

        return $this->format($results);
    }

    public function getAllTransactionsByGenre($genreId) {
        $sql = "SELECT 
                    b.image AS image,
                    b.title AS title,
                    b.author AS author,
                    b.ISBN AS ISBN,
                    br.first_name AS fname,
                    br.last_name AS lname,
                    bb.borrow_date AS borrow_date,
                    bb.due_date AS due_date,
                    bb.borrowed_id AS borrowed_id,
                    CASE 
                        WHEN bb.due_date < CURDATE() THEN 1 
                        ELSE 0 
                    END AS is_overdue
                FROM borrowed_books bb
                    LEFT JOIN books b ON bb.book_id = b.ISBN
                    LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code 
                WHERE b.genre_id = :genreId;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':genreId', $genreId, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function addTransaction($transaction) {
        $bookID = $transaction['book_id'] ?: null;
        $borrowerCode= $transaction['borrower_code'] ?: null;
        $dueDate = $transaction['due_date'] ?: null;

        $sql = "INSERT INTO `borrowed_books`(`book_id`, `borrower_code`, `due_date`) 
        VALUES (:bookID,:borrowerCode,:dueDate)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":bookID", $bookID, PDO::PARAM_STR);
        $stmt->bindValue(":borrowerCode", $borrowerCode, PDO::PARAM_STR);
        $stmt->bindValue(":dueDate", $dueDate, PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function deleteTransaction($id) {
        $sql = "DELETE FROM borrowed_books WHERE borrowed_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result;
    }

    public function searchTransaction($query) {
        $sql = "SELECT 
                    b.image AS image,
                    b.title AS title,
                    b.author AS author,
                    b.ISBN AS ISBN,
                    br.first_name AS fname,
                    br.last_name AS lname,
                    bb.borrow_date AS borrow_date,
                    bb.due_date AS due_date,
                    bb.borrowed_id AS borrowed_id,
                    CASE 
                        WHEN bb.due_date < CURDATE() THEN 1 
                        ELSE 0 
                    END AS is_overdue
                FROM borrowed_books bb
                    LEFT JOIN books b ON bb.book_id = b.ISBN
                    LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code
                WHERE b.title LIKE :query 
                OR b.author LIKE :query 
                OR b.ISBN LIKE :query 
                OR br.first_name LIKE :query
                OR br.last_name LIKE :query
                OR DATE_FORMAT(bb.borrow_date, '%M %d, %Y') LIKE :query
                OR DATE_FORMAT(bb.due_date, '%M %d, %Y') LIKE :query";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":query", "%{$query}%", PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->format($results);
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

    public function getTotalTransactionsByParam($conditions="", $params=[]) {
        $sql = "SELECT
                    COUNT(*) AS total
                FROM borrowed_books
                    WHERE $conditions
                ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function format($results): array|null {
        if($results == null) {
            return $results;
        }

        return array_map(function ($row): array {
            return [
                "id" => $row['borrowed_id'],
                "Book Info" => [
                    "Image" => formatImage($row['image'] ?? ""),
                    "ISBN" => $row['ISBN'] ?? '',
                    "Author" => $row['author'] ?? '',
                    "Title" => $row['title'] ?? ''
                ],
                "Borrower" => trim(($row['fname'] ?? '') . ' ' . ($row['lname'] ?? '')),
                "Borrow Date" => formatDate($row['borrow_date'] ?? ''),
                "Due Date" => formatDate($row['due_date'] ?? ''),
                "Status" => $row['is_overdue'] == 1 ? "Overdue" : "On time", // You can change this based on logic
            ];
        }, $results);
    }
}