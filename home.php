<main>
  <?php include "api/BooksController.php" ?>
  <?php $controller = new BooksController($conn); ?>

  <?php
  $books = [];
  if (isset($_POST['search'])) {
    $greeting = "Berikut hasil untuk pencarian " . htmlspecialchars($_POST['search']) . ":";
    $search = $_POST['search'];
    $books = array_merge([], $controller->searchBooksByTitle($search, 10), $controller->searchBooksByAuthor($search, 10));
    $books = array_unique($books, SORT_REGULAR);
  } else {
    if (isset($_SESSION['username'])) {
      $currentTime = date("H:i");
      $greeting = "Selamat ";
      if ($currentTime >= "04:00" && $currentTime < "12:00") {
        $greeting .= "Pagi";
      } else if ($currentTime >= "12:00" && $currentTime < "16:00") {
        $greeting .= "Siang";
      } else if ($currentTime >= "16:00" && $currentTime < "19:00") {
        $greeting .= "Sore";
      } else {
        $greeting .= "Malam";
      }
      $greeting .= ", " . htmlspecialchars($_SESSION['username']) . "!";
      $books = $controller->getBooks(10);
    } else {
      $greeting = "Selamat Datang!";
      $books = $controller->getBooks(10);
    }
  }
  echo('<h1>' . $greeting . '</h1>');
  if (isset($_SESSION['level'])) {
    if ($_SESSION['level'] == 1) {
      // Display books being currently borrowed
      echo('<p style="color: #555">Kamu sedang meminjam beberapa buku berikut:</p>');
      $controller->display_books($controller->getBorrowedBooksByValue('user_id', $_SESSION['id'], 10));

      // Display favorite books
      echo('<p style="color: #555">Kamu telah memfavoritkan beberapa buku ini:</p>');
      // $controller->display_books($controller->);
    } 
    elseif ($_SESSION['level'] == 2) {
      // Display list of books
      echo('<p style="color: #555">List buku-buku yang ada saat ini:</p>');
      // $controller->display_books($controller->); 

      // Display books currently being borrowed
      echo('<p style="color: #555">List buku-buku yang masih dipinjam saat ini:</p>');
      // $controller->display_books($controller->); 
    } 
  }
  echo('<p style="color: #555">Berikut beberapa buku yang kami rekomendasikan untuk kamu baca:</p>');
  $controller->display_books($books);
  ?> 
</main>
