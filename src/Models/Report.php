<?php
namespace App\Models;

use App\Core\Model;


class Report extends Model {
    public function getTotals(): array {
        $totals = [];

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM books");
        $totals['total_books'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM borrowers");
        $totals['total_borrowers'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM borrowed_books");
        $totals['total_borrowed'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
        $totals['total_staff'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

        return $totals;
    }

    public function getRecentBorrowed($limit = 4) {
        $sql = "SELECT
                bb.borrowed_id as ISBN, 
                b.title AS 'Book Title', 
                CONCAT(br.first_name, ' ', br.last_name) AS Borrower, 
                DATE_FORMAT(bb.borrow_date, '%M %d, %Y') AS 'Borrowed Date', 
                DATE_FORMAT(bb.due_date, '%M %d, %Y') AS 'Due Date'
            FROM borrowed_books bb
                LEFT JOIN books b ON bb.book_id = b.ISBN
                LEFT JOIN borrowers br ON bb.borrower_code = br.borrower_code
            ORDER BY bb.borrow_date DESC
                LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBooksByStatus() {
        $sql = "SELECT status, COUNT(*) as count FROM books GROUP BY status";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMonthlyBorrowed($months = 6) {
        $sql = "SELECT
                    DATE_FORMAT(bb.borrow_date, '%Y-%m') AS month,
                    COUNT(*) AS count
                FROM borrowed_books bb
                WHERE bb.borrow_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
                    GROUP BY month
                    ORDER BY month ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', (int)$months, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBooksReport($from = null, $to = null): array {
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

    public function getBorrowedBooksReport($from = null, $to = null): array {
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

    public function getBorrowersReport($from = null, $to = null): array {
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
