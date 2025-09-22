<?php

namespace App\Models;

use App\Core\Model;

class Book extends Model {

    public function addBook($book) {
        $ISBN = $book['ISBN'];
        $author = $book['author'];
        $title = $book['title'];
        $publisher = $book['publisher'];
        $genre = $book['genre'];
        $quantity = $book['quantity'];
        $status = $book['status'];

        $sql = "INSERT INTO `books`(`ISBN`, `genre_id`, `author`, `title`, `quantity`, `status`, `image`, `publisher`) 
        VALUES (:ISBN,:genre,:author,:title,:quantity,:status,:image,:publisher)";
    }

    public function removeBook() {

    }

    public function updateBook() {

    }

    public function getBook($id) {
        $sql = "SELECT * FROM books WHERE ISBN = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
    
    public function getAllBooks($sortBy = "author", $sortDir = "ASC"): array {
        // whitelist allowed columns for sorting (map to actual table.column)
        $allowedSort = [
            "title"  => "b.title",
            "author" => "b.author",
            "price"  => "b.price",
            "genre"  => "g.genre"
        ];

        // fallback if invalid sort
        $sortBy = $allowedSort[$sortBy] ?? "b.author";

        // whitelist directions
        $sortDir = strtoupper($sortDir) === "DESC" ? "DESC" : "ASC";

        $sql = "
            SELECT 
                b.*, 
                g.genre 
            FROM books b
            LEFT JOIN genres g ON b.genre_id = g.id
            ORDER BY $sortBy $sortDir
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getPaginatedBooks($limit, $offset, $sortBy = 'author', $sortDir = 'ASC', $search = ''): array {
        return $this->paginate(
        'books',
        ['ISBN','author','publisher','title','genre_id','quantity','status'],
        $limit,
        $offset,
        $sortBy,
        $sortDir,
        $search,
        ['ISBN','author','publisher','title'] // searchable columns
    );
    }

    public function getTotalBooks($search = ''): mixed {
        return $this->getTotal(
        'books',
        $search,
        ['ISBN','author','publisher','title','status'] // searchable columns
        );
    }

}
