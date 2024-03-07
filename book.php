<?php ob_start(); ?>
<main>
  <?php require "api/config.php"; ?>
  <?php require "api/BooksController.php"; ?>
  <?php $controller = new BooksController($conn); ?>
  <?php $book=$controller->getBooksByValue('id', $_GET['id'], null); ?>
  <?php 
  if(isset($_POST['pinjam'])){
    $controller->createBorrowedBook($_SESSION['id'], $book[0]['id']);
  }
  if(isset($_POST['kembali'])){
    $controller->deleteBorrowedBook($_SESSION['id'], $book[0]['id']);
  }
  if(isset($_POST['favorite'])){
    $controller->createFavoritedBook($_SESSION['id'], $book[0]['id']);
  }
  if(isset($_POST['unfavorite'])){
    $controller->deleteFavoritedBook($_SESSION['id'], $book[0]['id']);
  }
  if(isset($_POST['delete'])){
    $controller->deleteBook($book[0]['id']);
    echo "<script>window.location.href = '/e-perpustakaan'</script>";
  }
  ?>
  <style>
    .heart {
      width: 24px;
      height: 24px;
      border: none;
      background-repeat: no-repeat;
      background-size: contain;
      cursor: pointer;
      text-indent: -9999px;
    }
    .favorite {
      background-image: url('assets/favorite-blank.svg');
    }
    .unfavorite {
      background-image: url('assets/favorite-full.svg');
    }
  </style>
  <div class="title">
    <?php if(isset($_SESSION['level'])) {
      if($_SESSION['level'] == 1) { ?>
        <div class="option">
          <div style="display: flex; justify-content: center; align-items: center">
            <form method="post">
              <?php
              $sql = "SELECT * FROM favorites WHERE book_id = " . $book[0]['id'] . " AND user_id = " . $_SESSION['id'];
              $result = $conn->query($sql);
              if ($result->num_rows > 0) { ?>
                <input type='submit' name="unfavorite" id='unfavorite' style='margin: 5px;' class='heart unfavorite'>
                <!-- <img src='assets/favorite-full.svg' style='margin-right: 8px; width: 24px;'> -->
              <?php } else { ?>
                <input type='submit' name="favorite" id='favorite' style='margin: 5px' class='heart favorite'>
                <!-- <img src='assets/favorite-blank.svg' style='margin-right: 8px; width: 24px;'> -->
              <?php } ?>
            </form>
            <font color="#555">
              <?php
                $favorites = $controller->getFavoritedBooks(null);
                $favoritesCount = 0;
                foreach ($favorites as $favorite) {
                  if ($favorite['book_id'] == $_GET['id']) {
                    $favoritesCount++;
                  }
                }
                echo $favoritesCount;
              ?>
            </font>
          </div>
          <form method="post">
            <?php
            $sql = "SELECT * FROM borrows WHERE book_id = " . $book[0]['id'] . " AND user_id = " . $_SESSION['id'];
            $result = $conn->query($sql);
            if ($result->num_rows > 0) { ?>
              <input type='submit' name="kembali" id='kembali' style='margin: 25px;' class='pinjam' value='Kembalikan'>
            <?php } else { ?>
              <input type='submit' name="pinjam" id='pinjam' style='margin: 25px;' class='pinjam' value='Pinjam'>
            <?php } ?>
          </form>
        </div>
      <?php } elseif($_SESSION['level'] == 2) { ?>
        <div class="option">
          <form method="post">
            <?php
            $sql = "SELECT * FROM favorites WHERE book_id = " . $book[0]['id'] . " AND user_id = " . $_SESSION['id'];
            $result = $conn->query($sql);
            if ($result->num_rows > 0) { ?>
              <input type='submit' name="unfavorite" id='unfavorite' style='margin: 5px; margin-top: 35px' class='heart unfavorite'>
              <!-- <img src='assets/favorite-full.svg' style='margin-right: 8px; width: 24px;'> -->
            <?php } else { ?>
              <input type='submit' name="favorite" id='favorite' style='margin: 5px; margin-top: 35px' class='heart favorite'>
              <!-- <img src='assets/favorite-blank.svg' style='margin-right: 8px; width: 24px;'> -->
            <?php } ?>
          </form>
          <font color="#555" style="margin-top: 33px">
            <?php
              $favorites = $controller->getFavoritedBooks(null);
              $favoritesCount = 0;
              foreach ($favorites as $favorite) {
                if ($favorite['book_id'] == $_GET['id']) {
                  $favoritesCount++;
                }
              }
              echo $favoritesCount;
            ?>
          </font>
          <form method="post">
            <input type='submit' name="delete" id='delete' style='margin: 25px; margin-left: 10px;' class='delete' value="Delete" onclick="confirm('Apakah anda yakin?')">
          </form>
        </div>
      <?php } ?>
    <?php } ?>
    <?php echo '<img src="data:image/jpeg;base64,' . $book[0]['cover'] . '" width="50" height="75"/>'; ?>
    <h1><?=$book[0]['title']?></h1>
    <p style='color: #555'><?=$book[0]['author']?></p>
  </div>
  <hr color="black">
  <br>
  <?php
  $pdf = $book[0]['text'];
  ?>
<script src="https://mozilla.github.io/pdf.js/build/pdf.mjs" type="module"></script>

<script type="module">
  let pdfData = atob("<?= $pdf ?>");

  let { pdfjsLib } = globalThis;

  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.mjs';

  let loadingTask = pdfjsLib.getDocument({data: pdfData});
  loadingTask.promise.then(function(pdf) {
    for (let i = 1; i <= <?php echo $book[0]['pages'] ?>; i++) {
      pdf.getPage(i).then(function(page) {

      let scale = window.innerWidth < 600 ? 0.5 : 1.5;
      let viewport = page.getViewport({scale: scale});

      let canvas = document.getElementById('page_' + i);
      let context = canvas.getContext('2d');
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      let renderContext = {
        canvasContext: context,
        viewport: viewport
      };
      let renderTask = page.render(renderContext);
      renderTask.promise.then(function () {
      });
    });
    }
  }, function (reason) {
    console.error(reason);
  });
</script>

<script>
  setInterval(function() {
    if (isNaN(localStorage.getItem('timeReading'))) {
      localStorage.setItem('timeReading', 0);
    } else {
      localStorage.setItem("timeReading", parseInt(localStorage.getItem('timeReading')) + 1);
    }
  }, 1000)
</script>
<div style="display: flex; justify-content: center; align-items: center; flex-direction: column; max-width: 100vw;">
  <?php
  if (isset($_SESSION['id'])) {
    $sql = "SELECT * FROM borrows WHERE book_id = " . $book[0]['id'] . " AND user_id = " . $_SESSION['id'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0 || $_SESSION['level'] == 2) {
      for ($i = 1; $i <= $book[0]['pages']; $i++) {
        echo '<canvas id="page_' . $i . '"></canvas>';
      }
    } else {
      for ($i = 1; $i <= 20; $i++) {
        echo '<canvas id="page_' . $i . '"></canvas>';
      }
      if($_SESSION['level'] == 1) {
        echo '<p style="color: #009">(pinjam buku untuk membaca lebih lanjut)</p>';
      }
    } 
  } else {
    for ($i = 1; $i <= 20; $i++) {
      echo '<canvas id="page_' . $i . '"></canvas>';
    }
    echo '<p style="color: #009">(login untuk membaca lebih lanjut)</p>';
  }
  ?>
</div>
</main>
<?php ob_end_flush(); ?>