<?php

namespace App\Models;

use App\Core\Model;

class BorrowedBook extends Model {

    public function getAllTransactions() {
        
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
}