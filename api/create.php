<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    require "BooksController.php";
    $controller = new BooksController($conn);
    if($_POST['state'] == "book") {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $cover = $_FILES['cover'];
        $targetDir = './';
        $targetFileCover = $targetDir . basename($cover['name']);
        move_uploaded_file($cover['tmp_name'], $targetFileCover);
        $cover = base64_encode(file_get_contents($targetFileCover));
        unlink($targetFileCover);
        $text= $_FILES['text'];
        $targetDir = './';
        $targetFilePages = $targetDir . basename($text['name']);
        move_uploaded_file($text['tmp_name'], $targetFilePages);
        $text = base64_encode(file_get_contents($targetFilePages));
        unlink($targetFilePages);
        $pages = $_POST['pages'];

        $controller->createBook($title, $author, $cover, $text, $pages);
        header("Location: /e-perpustakaan?success=1");
    }
}