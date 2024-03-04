<main>
  <?php require "api/config.php"; ?>
  <?php require "api/BooksController.php"; ?>
  <?php $controller = new BooksController($conn); ?>
  <?php $book=$controller->getBooksByValue('id', $_GET['id'], null); ?>
  <div class="title">
    <?php if(isset($_SESSION['level'])) {
      if($_SESSION['level'] == 1) { ?>
        <div class="option">
          <div style="display: flex; justify-content: center; align-items: center">
            <img src="assets/favorite-blank.svg" style="margin-right: 8px; width: 24px;"><font color="#555">
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
          <button id='pinjam' style='margin: 25px;' class='pinjam'>Pinjam</button>
        </div>
      <?php } elseif($_SESSION['level'] == 2) { ?>
        <div class="option">
          <img src="assets/favorite-blank.svg" style="margin-right: 8px; width: 24px;"><font color="#555" style="margin-top: 33px">
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
          <button id='edit' style='margin: 25px; margin-right: 10px;' class='edit'>Edit</button>
          <button id='delete' style='margin: 25px; margin-left: 10px;' class='delete'>Delete</button>
        </div>
      <?php } ?>
    <?php } ?>
    <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($book[0]['cover']) . '" width="50" height="75"/>'; ?>
    <h1><?=$book[0]['title']?></h1>
    <p style='color: #555'><?=$book[0]['author']?></p>
  </div>
  <hr color="black">
  <br>
  <?php
  $pdf = base64_encode($book[0]['text']);
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
    localStorage.setItem("timeReading", parseInt(localStorage.getItem('timeReading')) + 1);
  }, 1000)
</script>
<div style="display: flex; justify-content: center; align-items: center; flex-direction: column; max-width: 100vw;">
  <?php
  if (isset($_SESSION['id'])) {
    $sql = "SELECT * FROM borrows WHERE book_id = " . $book[0]['id'] . " AND user_id = " . $_SESSION['id'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
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