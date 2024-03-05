<?php
// $conn = new mysqli("0.tcp.ap.ngrok.io", "root", "", "e-perpustakaan", "18367");
$conn = new mysqli("localhost", "root", "", "e-perpustakaan");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
