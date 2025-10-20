<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ReturnedBook extends Model {

    public function addReturnedBook($returnedBook): bool {
        $borrowedID = $returnedBook['borrowed_id'] ?: null;
        $bookID = $returnedBook['ISBN'] ?: null;
        $borrowerCode= $returnedBook['borrower_code'] ?: null;
        $borrowDate = $returnedBook['borrow_date'] ?: null;
        $dueDate = $returnedBook['due_date'] ?: null;

        $sql = "INSERT INTO `returned_books`(`borrowed_id` ,`book_id`, `borrower_code`, `borrow_date`, `due_date`) 
        VALUES (:borrowedID,:bookID,:borrowerCode,:borrowDate,:dueDate)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":borrowedID", $borrowedID, \PDO::PARAM_STR);
        $stmt->bindValue(":bookID", $bookID, \PDO::PARAM_STR);
        $stmt->bindValue(":borrowerCode", $borrowerCode, \PDO::PARAM_STR);
        $stmt->bindValue(":borrowDate", $borrowDate, \PDO::PARAM_STR);
        $stmt->bindValue(":dueDate", $dueDate, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function deleteReturnedBook($id) {
        $sql = "DELETE FROM returned_books WHERE returned_book_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result;
    }

    public function getReturnedBooks(): array|null {
        $sql = "SELECT  
                        rb.returned_book_id,
                        rb.borrower_code,
                        DATE_FORMAT(rb.return_date, '%M %d, %Y') AS return_date,
                        DATE_FORMAT(rb.borrow_date, '%M %d, %Y') AS borrow_date,
                        DATE_FORMAT(rb.due_date, '%M %d, %Y') AS due_date,
                        rb.book_id,
                        rb.borrower_code,
                        rb.borrowed_id,
                        b.image,
                        b.title,
                        b.author,
                        br.first_name,
                        br.last_name
                    FROM returned_books rb
                        LEFT JOIN borrowed_books bb ON rb.borrowed_id = bb.borrowed_id
                        LEFT JOIN books b ON rb.book_id = b.ISBN
                        LEFT JOIN borrowers br ON rb.borrower_code = br.borrower_code";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: null;
        $formattedResults = $this->format($results);

        return $formattedResults ?: null;
    }

    public function searchReturnedBooks($query) {
        $sql = "SELECT  
                    rb.returned_book_id,
                    rb.borrower_code,
                    DATE_FORMAT(rb.return_date, '%M %d, %Y') AS return_date,
                    DATE_FORMAT(rb.borrow_date, '%M %d, %Y') AS borrow_date,
                    DATE_FORMAT(rb.due_date, '%M %d, %Y') AS due_date,
                    rb.book_id,
                    rb.borrower_code,
                    rb.borrowed_id,
                    b.image,
                    b.title,
                    b.author,
                    br.first_name,
                    br.last_name
                FROM returned_books rb
                    LEFT JOIN borrowed_books bb ON rb.borrowed_id = bb.borrowed_id
                    LEFT JOIN books b ON rb.book_id = b.ISBN
                    LEFT JOIN borrowers br ON rb.borrower_code = br.borrower_code
                WHERE b.title LIKE :query
                OR b.author LIKE :query
                OR rb.book_ID LIKE :query
                OR br.first_name LIKE :query
                OR br.last_name LIKE :query
                OR DATE_FORMAT(rb.return_date, '%M %d, %Y') LIKE :query
                OR DATE_FORMAT(rb.borrow_date, '%M %d, %Y') LIKE :query
                OR DATE_FORMAT(rb.due_date, '%M %d, %Y') LIKE :query
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":query", "%{$query}%", \PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $formattedResults = $this->format($results);

        return $formattedResults ?: null;
    }

    public function getAllReturnedBooksByGenre($genreId) {
        $sql = "SELECT  
                        rb.returned_book_id,
                        rb.borrower_code,
                        DATE_FORMAT(rb.return_date, '%M %d, %Y') AS return_date,
                        DATE_FORMAT(rb.borrow_date, '%M %d, %Y') AS borrow_date,
                        DATE_FORMAT(rb.due_date, '%M %d, %Y') AS due_date,
                        rb.book_id,
                        rb.borrower_code,
                        rb.borrowed_id,
                        b.image,
                        b.title,
                        b.author,
                        br.first_name,
                        br.last_name
                    FROM returned_books rb
                        LEFT JOIN borrowed_books bb ON rb.borrowed_id = bb.borrowed_id
                        LEFT JOIN books b ON rb.book_id = b.ISBN
                        LEFT JOIN borrowers br ON rb.borrower_code = br.borrower_code
                    WHERE b.genre_id = :genreId
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':genreId', $genreId, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function format($results): array|null {
        if($results == null) {
            return $results;
        }

        return array_map(function ($row) {
            return [
                "id" => $row['returned_book_id'],
                "Book Info" => [
                    "Image" => $row['image'] ?? '',
                    "ISBN" => $row['book_id'] ?? '',
                    "Author" => $row['author'] ?? '',
                    "Title" => $row['title'] ?? ''
                ],
                "Borrower" => trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
                "Borrow Date" => $row['borrow_date'] ?? '',
                "Due Date" => $row['due_date'] ?? '',
                "Return Date" => $row['return_date'] ?? '',
                "Status" => "Returned", // You can change this based on logic
            ];
        }, $results);
    }
    
}