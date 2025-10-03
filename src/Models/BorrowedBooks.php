<?php

namespace App\Models;

use App\Core\Model;

class BorrowedBooks extends Model {
    public function addTransaction($transaction) {
        $bookID = $transaction['bookID'] ?: null;
        $borrowerID = $transaction['borrowerID'] ?: null;
        $borrowDate = $transaction['borrowedDate'] ?: null;
        $dueDate = $transaction['dueDate'] ?: null;
        $returnDate = $transaction['returnDate'] ?: null;
        $status = $transaction['status'] ?: 'Borrowed';

        $sql = "INSERT INTO `borrowed_books`(`book_id`, `borrower_id`, `borrow_date`, `due_date`, `return_date`, `status`) 
        VALUES (:bookID,:borrowerID,:borrowDate,:dueDate,:returnDate,:status";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":bookID", $bookID, \PDO::PARAM_STR);
        $stmt->bindValue(":borrowerID", $borrowerID, \PDO::PARAM_INT);
        $stmt->bindValue(":borrowDate", $borrowDate, \PDO::PARAM_STR);
        $stmt->bindValue(":dueDate", $dueDate, \PDO::PARAM_STR);
        $stmt->bindValue(":returnDate", $returnDate, \PDO::PARAM_STR);
        $stmt->bindValue(":status", $status, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }
}