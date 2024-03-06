<main>
  <?php require "api/BooksController.php" ?>
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
      $controller->display_books($controller->getBorrowedBooksById($controller->getBorrowedBooksByValue('user_id', $_SESSION['id'], null), null), 'book', "pinjam");

      // Display favorite books
      echo('<p style="color: #555">Kamu telah memfavoritkan beberapa buku ini:</p>');
      $controller->display_books($controller->getFavoritedBooksById($controller->getFavoritedBooksByValue('user_id', $_SESSION['id'], null), null), 'book', "favorite");
      
      echo('<p style="color: #555">Berikut beberapa buku yang kami rekomendasikan untuk kamu baca:</p>');
      $controller->display_books($books);
    } 
    elseif ($_SESSION['level'] == 2) {
      // Display list of books
      echo('<p style="color: #555">List buku-buku yang ada saat ini:</p>');
      $controller->display_books($books, 'book', "create");

      // Display books currently being borrowed
      echo('<p style="color: #555">List buku-buku yang masih dipinjam saat ini:</p>');
      $controller->display_books($controller->getBorrowedBooksById($controller->getBorrowedBooks(null), null), 'book', "pinjam");
    } 
  } else {
    echo('<p style="color: #555">Berikut beberapa buku yang kami rekomendasikan untuk kamu baca:</p>');
    $controller->display_books($books);
  }
  ?> 
</main>

<div class="create-form hidden" id="createForm">
  <form action="api/create.php" method="post" id="createPopup" enctype="multipart/form-data">
    <img src="assets/logo.svg" alt="logo" width="48" style="align-self: center;">
    <h2 style="align-self: center; font-weight: 400;">E-Perpustakaan</h2>
    <br><hr><br>
    <input type="text" name="state" value="book" hidden>
    <label for="title">Title</label>
    <div class="form-group">
      <!-- <img src="assets/mail.svg" alt="mail" width="16"> -->
      <input class="form-input" type="text" name="title" id="title" placeholder="Masukkan title...">
    </div>
    <label for="author">Author</label>
    <div class="form-group">
      <input class="form-input" type="text" name="author" id="author" placeholder="Masukkan author...">
    </div>
    <label for="cover">Cover</label>
    <div class="form-group">
      <input class="form-input" type="file" name="cover" id="cover" placeholder="Masukkan cover...">
    </div>
    <label for="text">Text</label>
    <div class="form-group">
      <input class="form-input" type="file" name="text" id="text" placeholder="Masukkan text...">
    </div>
    <label for="pages">Pages</label>
    <div class="form-group">
      <input class="form-input" type="number" name="pages" id="pages" placeholder="Masukkan pages...">
    </div>
    <div style="display: flex; justify-content: center;">
      <input class="form-button create" type="submit" value="Create">
      <input class="form-button reset" type="reset" value="Reset">
    </div>
  </form>
</div>

<script>
  document.querySelector('#createButton').addEventListener('click', function() {
    document.querySelector('#createForm').classList.remove('hidden');
  });
  window.onclick = function(event) {
    if (event.target == document.querySelector('#createForm')) {
      document.querySelector('#createForm').classList.add('hidden');
    }
  }
</script>