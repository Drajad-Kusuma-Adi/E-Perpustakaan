<?php
require_once 'CRUDController.php';
class BooksController extends CRUDController
{
    private $conn;
    private $controller;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->controller = new CRUDController($conn);
    }

    public function display_books($books, $page = 'book')
    {
        if (empty($books)) {
            echo "<p style='color: #555'>Mohon maaf! Tidak ada hasil pencarian yang sesuai.</p>";
            return;
        }

        echo "<div class='books-container'>";
        foreach ($books as $book) {
            echo "<div class='bookcard'>";
            echo '<img src="data:image/jpeg;base64,' . base64_encode($book['cover']) . '" width="200" height="300"/><br>';
            echo "<h3 style='margin-top: 5px'>" . htmlspecialchars($book['title']) . "</h3>";
            echo "<p style='color: #555'>" . htmlspecialchars($book['author']) . "</p><br>";
            echo "<a href='?page=$page&id=" . htmlspecialchars($book['id']) . "'><button class='baca'>Baca</button></a>";
            echo "</div>";
        }
        echo "</div>";
    }

    // CRUD Operations
    public function createBook($title, $author, $cover, $text)
    {
        $this->controller->create('books', 'title, author, cover, text', "'$title', '$author', '$cover', '$text");
    }
    public function updateBook($title, $author, $cover, $text)
    {
        $this->controller->update('books', 'title, author, cover, text', "'$title', '$author', '$cover', '$text'");
    }
    public function deleteBook($id) {
        $this->controller->delete('books', 'id', $id);
    }
    public function getBooks($limit) {
        if ($limit != null) {
            return $this->controller->readMultiple('books', $limit);
        } else {
            return $this->controller->readMultiple('books', null);
        }
    }
    public function getBooksByValue($valName, $value, $limit) {
        if ($limit != null) {
            return $this->controller->readByValue('books', $valName, $value, $limit);
        } else {
            return $this->controller->readByValue('books', $valName, $value, null);
        }
    }
    public function searchBooksByTitle($search, $limit) {
        if ($limit != null) {
            return $this->controller->readByLike('books', 'title', $search, $limit);
        } else {
            return $this->controller->readByLike('books', 'title', $search, null);
        }
    }
    public function searchBooksByAuthor($search, $limit) {
        if ($limit != null) {
            return $this->controller->readByLike('books', 'author', $search, $limit);
        } else {
            return $this->controller->readByLike('books', 'author', $search, null);
        }
    }
    public function getBorrowedBooks($limit)
    {
        if ($limit != null) {
            return $this->controller->readMultiple('borrows', $limit);
        } else {
            return $this->controller->readMultiple('borrows', null);
        }
    }
    public function getFavoritedBooks($limit)
    {
        if ($limit != null) {
            return $this->controller->readMultiple('favorites', $limit);
        } else {
            return $this->controller->readMultiple('favorites', null);
        }
    }

    public function getBorrowedBooksByValue($valName, $value, $limit)
    {
        if ($limit != null) {
            return $this->controller->readByValue('borrows', $valName, $value, $limit);
        } else {
            return $this->controller->readByValue('borrows', $valName, $value, null);
        }
    }

    public function getFavoritedBooksByValue($valname, $value, $limit)
    {
        if ($limit != null) {
            return $this->controller->readByValue('favorites', $valname, $value, $limit);
        } else {
            return $this->controller->readByValue('favorites', $valname, $value, null);
        }
    }

    public function getBorrowedBooksById($borrowed, $limit)
    {
        if ($limit != null) {
            return $this->controller->readByValue('books', 'id', $borrowed[0]['book_id'], $limit);
        } else {
            return $this->controller->readByValue('books', 'id', $borrowed[0]['book_id'], null);
        }
    }

    public function getFavoritedBooksById($favorited, $limit)
    {
        if ($limit != null) {
            return $this->controller->readByValue('books', 'id', $favorited[0]['book_id'], null);
        } else {
            return $this->controller->readByValue('books', 'id', $favorited[0]['book_id'], null);
        }
    }

    public function getAllBorrowedBooks($borrowed, $limit)
    {
        if ($limit != null) {
            return $this->controller->readByValue('books', 'id', $borrowed[0]['book_id'], $limit);
        } else {
            return $this->controller->readByValue('books', 'id', $borrowed[0]['book_id'], null);
        }
    }
}
