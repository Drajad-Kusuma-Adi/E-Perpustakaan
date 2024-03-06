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

    public function display_books($books, $page = 'book', $state = null)
    {
        if (empty($books)) {
            if($state == "pinjam") {
                echo "<p style='color: #555'>Tidak ada buku yang dipinjam.</p>";
            } elseif($state == "favorite") {
                echo "<p style='color: #555'>Tidak ada buku yang difavoritkan.</p>";
            } else {
                echo "<p style='color: #555'>Mohon maaf! Tidak ada hasil pencarian yang sesuai.</p>";
            }
            return;
        }

        echo "<div class='books-container'>";
        foreach ($books as $book) {
            if($state != "create") {
                echo "<div class='bookcard'>";
                echo '<img src="data:image/jpeg;base64,' . base64_encode($book['cover']) . '" width="200" height="300"/><br>';
                echo "<h3 style='margin-top: 5px'>" . htmlspecialchars($book['title']) . "</h3>";
                echo "<p style='color: #555'>" . htmlspecialchars($book['author']) . "</p><br>";
                echo "<a href='?page=$page&id=" . htmlspecialchars($book['id']) . "'><button class='baca'>Baca</button></a>";
                echo "</div>";
            } else {
                echo "<div class='bookcard'>";
                echo "<button id='createButton' style='margin: 24px;' class='create'>+Tambahkan Buku</button>";
                echo "</div>";
            }
        }
        echo "</div>";
    }

    // CRUD Operations
    public function createBook($title, $author, $cover, $text, $pages)
    {
        $this->controller->create('books', 'title, author, cover, text, pages', "'$title', '$author', '$cover', '$text', '$pages'");
    }
    public function updateBook($title, $author, $cover, $text, $pages)
    {
        $this->controller->update('books', 'title, author, cover, text, pages', "'$title', '$author', '$cover', '$text', '$pages'");
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
        $books=[];
        foreach ($borrowed as $borrow) {
            $bookid = $borrow['book_id'];
            if ($limit != null) {
                $books = array_merge($books, $this->controller->readByValue('books', 'id', $bookid, $limit));
            } else {
                $books = array_merge($books, $this->controller->readByValue('books', 'id', $bookid, null));
            }
        }
        return $books;
    }

    public function getFavoritedBooksById($favorited, $limit)
    {
        $books=[];
        foreach ($favorited as $favorite) {
            $bookid = $favorite['book_id'];
            if ($limit != null) {
                $books = array_merge($books, $this->controller->readByValue('books', 'id', $bookid, $limit));
            } else {
                $books = array_merge($books, $this->controller->readByValue('books', 'id', $bookid, null));
            }
        }
        return $books;
    }

    public function createBorrowedBook($userid, $bookid)
    {
        $this->controller->create('borrows', 'user_id, book_id', "'$userid', '$bookid'");
    }
    public function deleteBorrowedBook($userid, $bookid)
    {
        $this->controller->delete('borrows', '(user_id, book_id)', "('$userid', '$bookid')");
    }
    public function createFavoritedBook($userid, $bookid)
    {
        $this->controller->create('favorites', 'user_id, book_id', "'$userid', '$bookid'");
    }
    public function deleteFavoritedBook($userid, $bookid)
    {
        $this->controller->delete('favorites', '(user_id, book_id)', "('$userid', '$bookid')");
    }
}
