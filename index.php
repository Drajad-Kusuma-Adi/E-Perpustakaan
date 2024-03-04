<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include "api/config.php";
    include "header.php";
    if (isset($_GET['logout'])) {
        include "logout.php";
    } else {
        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                case 'book':
                    $title = "E-Perpustakaan - Buku";
                    include "book.php";
                    break;
                case 'profile':
                    $title = "E-Perpustakaan - Profile";
                    include "profile.php";
                    break;
                case 'account':
                    $title = "E-Perpustakaan - Akun";
                    include "account.php";
                    break;
                default:
                    $title = "E-Perpustakaan - Dashboard";
                    include "home.php";
                    break;
            }
        } else {
            $title = "E-Perpustakaan - Dashboard";
            include "home.php";
        }
    }
    ?>
    <title><?= $title ?></title>
</body>

</html>