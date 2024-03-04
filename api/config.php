<?php
$conn = new mysqli("0.tcp.ap.ngrok.io", "root", "", "e-perpustakaan", "18367");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
