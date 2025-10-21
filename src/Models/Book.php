<?php

namespace App\Models;

use App\Core\Model;

class Book extends Model {

    public function addBook($book): bool {
        $ISBN = $book['ISBN'] ?: null;
        $author = $book['author'] ?: null;
        $title = $book['title'] ?: null;
        $publisher = $book['publisher'] ?: "No publisher";
        $genre = $book['genre'] ?: null;
        $quantity = $book['quantity'] ?: 0;
        $status = $book['status'];
        $image = $book['image'] ?: null;

        $sql = "INSERT INTO `books`(`ISBN`, `genre_id`, `author`, `title`, `quantity`, `status`, `image`, `publisher`) 
        VALUES (:ISBN,:genre,:author,:title,:quantity,:status,:image,:publisher)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":ISBN", $ISBN, \PDO::PARAM_STR);
        $stmt->bindValue(":author", $author, \PDO::PARAM_STR);
        $stmt->bindValue(":title", $title, \PDO::PARAM_STR);
        $stmt->bindValue(":publisher", $publisher, \PDO::PARAM_STR);
        $stmt->bindValue(":genre", $genre, \PDO::PARAM_INT);
        $stmt->bindValue(":quantity", $quantity, \PDO::PARAM_INT);
        $stmt->bindValue(":status", $status, \PDO::PARAM_STR);
        $stmt->bindValue(":image", $image, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function removeBook($ISBN) {
        $sql = "DELETE FROM books WHERE ISBN = :ISBN";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":ISBN", $ISBN, \PDO::PARAM_STR);
        $result = $stmt->execute();

        return $result;
    }

    public function updateBook($id, $updates): bool {
        $ISBN = $updates['ISBN'] ?: null;
        $author = $updates['author'] ?: null;
        $title = $updates['title'] ?: null;
        $publisher = $updates['publisher'] ?: "No publisher";
        $genre = $updates['genre'] ?: null;
        $quantity = $updates['quantity'] ?: 0;
        $status = $updates['status'];
        $image = $updates['image'] ?: null;

        $sql = "UPDATE books 
                SET 
                ISBN = :ISBN,
                genre_id = :genre,
                author = :author,
                title = :title,
                quantity = :quantity,
                status = :status,
                image = :image,
                publisher = :publisher
                WHERE ISBN = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":ISBN", $ISBN, \PDO::PARAM_STR);
        $stmt->bindValue(":genre", $genre, \PDO::PARAM_INT);
        $stmt->bindValue(":author", $author, \PDO::PARAM_STR);
        $stmt->bindValue(":title", $title, \PDO::PARAM_STR);
        $stmt->bindValue(":quantity", $quantity, \PDO::PARAM_INT);
        $stmt->bindValue(":status", $status, \PDO::PARAM_STR);
        $stmt->bindValue(":image", $image, \PDO::PARAM_STR);
        $stmt->bindValue(":publisher", $publisher, \PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getBook($id) {
        if(empty($id)) {
            throw new \Exception('Book ISBN/ID required');
        }

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
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function getAllBooksByGenre($genreId) {
        $sql = "SELECT 
                    b.*, 
                    g.genre 
                FROM books b
                    LEFT JOIN genres g ON b.genre_id = g.id
                WHERE genre_id = :genreId";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":genreId", $genreId, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function searchBooks($query) {
        $sql = "SELECT 
                    b.ISBN AS ISBN,
                    b.author AS author,
                    b.title AS title,
                    b.quantity AS quantity,
                    b.status AS status,
                    g.genre AS genre,
                    b.image AS image
                FROM books b
                    LEFT JOIN genres g ON b.genre_id = g.id
                WHERE b.title LIKE :query 
                    OR b.author LIKE :query 
                    OR b.ISBN LIKE :query
                    OR g.genre LIKE :query
                    OR b.quantity LIKE :query
                    OR b.status LIKE :query";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":query", "%$query%", \PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->format($results);
    }

    public function addQuantity($id, $quantity=1) {
        $book = $this->getBook($id);

        $sql = "UPDATE books 
                SET 
                quantity = :quantity
                WHERE ISBN = :id";

        $newQuantity = $book['quantity'] + $quantity;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":quantity", $newQuantity, \PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function deductQuantity($id, $quantity=1) {
        $book = $this->getBook($id);

        $sql = "UPDATE books 
                SET 
                quantity = :quantity
                WHERE ISBN = :id";

        $newQuantity = $book['quantity'] - $quantity;

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":quantity", $newQuantity, \PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, \PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getTotalBooks($search = ''): mixed {
        return $this->getTotal(
        'books',
        $search,
        ['ISBN','author','publisher','title','status'] // searchable columns
        );
    }

    public function format($results): array|null {
        if($results == null) {
            return $results;
        }

        return array_map(function ($row) {
            return [
                "ISBN" => $row['ISBN'],
                "Image" => formatImage($row['image'] ?? ""),
                "Author" => $row['author'],
                "Title" => $row['title'],
                "Genre" => $row['genre'],
                "Quantity" => $row['quantity'] ?? 0,
                "Status" => $row['quantity'] > 0 ? "Available" : "Unavailable"
            ];
        }, $results);
    }

}
