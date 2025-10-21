<?php
namespace App\Models;

use App\Core\Model;


class Report extends Model {
    public function all()
    {
        // Dummy reports
        return [
            [
                'id' => 1,
                'title' => 'Monthly Borrowed Books Report',
                'date' => '2025-10-01',
                'description' => 'Shows all borrowed books for the month of October.',
                'file' => 'monthly_borrowed_books.pdf'
            ],
            [
                'id' => 2,
                'title' => 'Overdue Books Report',
                'date' => '2025-10-10',
                'description' => 'Displays a list of overdue books and their borrowers.',
                'file' => 'overdue_books.pdf'
            ],
            [
                'id' => 3,
                'title' => 'New Users Registered Report',
                'date' => '2025-09-30',
                'description' => 'Summary of newly registered users in September.',
                'file' => 'new_users.pdf'
            ]
        ];
    }

    public function getBooksReport($from = null, $to = null) {
        $sql = "SELECT 
                b.title AS Title, 
                b.author AS Author, 
                g.genre AS Genre, 
                b.status AS Status, 
                DATE_FORMAT(b.created_at, '%M %d, %Y') AS AddedDate 
            FROM books b
                LEFT JOIN genres g ON b.genre_id = g.id";
        $params = [];

        if ($from && $to) {
            $sql .= " WHERE DATE(created_at) BETWEEN :from AND :to";
            $params = [":from" => $from, ":to" => $to];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGenresReport() {
        $sql = "SELECT genre AS Genre, description AS Description FROM genres";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBorrowedBooksReport($from = null, $to = null) {
        $sql = "SELECT 
                    b.title AS Book, 
                    CONCAT(br.first_name, ' ', br.last_name) AS Borrower, 
                    DATE_FORMAT(rb.borrow_date, '%M %d, %Y') AS BorrowedDate, 
                    DATE_FORMAT(rb.return_date, '%M %d, %Y') AS ReturnedDate
                FROM returned_books rb
                JOIN books b ON rb.book_id = b.ISBN
                JOIN borrowers br ON rb.borrower_code = br.borrower_code";
        $params = [];

        if ($from && $to) {
            $sql .= " WHERE DATE(rb.borrow_date) BETWEEN :from AND :to";
            $params = [":from" => $from, ":to" => $to];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBorrowersReport($from = null, $to = null) {
        $sql = "SELECT 
                    CONCAT(first_name, ' ', last_name) AS Name, 
                    email AS Email, 
                    status AS Status, 
                    DATE_FORMAT(membership_date, '%M %d, %Y') AS 'Membership Date' 
                FROM borrowers";
        $params = [];

        if ($from && $to) {
            $sql .= " WHERE DATE(membership_date) BETWEEN :from AND :to";
            $params = [":from" => $from, ":to" => $to];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
