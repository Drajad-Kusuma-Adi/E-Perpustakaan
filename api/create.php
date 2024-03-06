<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config.php';
    require "BooksController.php";
    $controller = new BooksController($conn);
    if($_POST['state'] == "book") {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $cover = $_FILES['cover'];//cover png file not working
        $text= $_FILES['text'];//text pdf file not working
        $pages = $_POST['pages'];

        $controller->createBook($title, $author, $cover, $text, $pages);
        header("Location: /e-perpustakaan?success=1");
    }
}