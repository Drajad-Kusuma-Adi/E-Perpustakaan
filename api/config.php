<?php
$conn = new mysqli("localhost", "root", "", "e-perpustakaan");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
