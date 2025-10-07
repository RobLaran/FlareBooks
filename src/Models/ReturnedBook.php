<?php

namespace App\Models;

use App\Core\Model;

class ReturnedBook extends Model {

    public function addReturnedBook($returnedBook) {
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
    
}