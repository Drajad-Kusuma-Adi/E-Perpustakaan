<?php
session_start();

$image = $_FILES['image'];
$targetFile = './' . basename($image['name']);
move_uploaded_file($image['tmp_name'], $targetFile);

$base64_image = base64_encode(file_get_contents($targetFile));

require "config.php";
require "UsersController.php";
$controller = new UsersController($conn);
$controller->updateProfilePhoto($_SESSION['id'], $base64_image);

$_SESSION['image'] = $base64_image;

unlink($targetFile);

echo "<script>window.location.href = '/e-perpustakaan?page=profile'</script>";