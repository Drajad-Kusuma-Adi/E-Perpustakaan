<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config.php';
    include 'CRUDController.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    $controller = new CRUDController($conn);

    $user = $controller->readByValue('users', 'username', $username, 1);
    if (!$user) {
        echo "Username not found";
        header("Location: /e-perpustakaan?failed=1");
    } else {
        $_SESSION['id'] = $user[0]['id'];
        $_SESSION['username'] = $user[0]['username'];
        $_SESSION['level'] = $user[0]['level'];
        $_SESSION['image'] = $user[0]['image'];
        header("Location: /e-perpustakaan?success=1");
    }
}