<main>
  <?php require "api/config.php" ?>
  <?php require "api/UsersController.php" ?>
  <?php require "api/BooksController.php" ?>
  <?php
  $UsersController = new UsersController($conn);
  $BooksController = new BooksController($conn);

  $user = $UsersController->getUserDataById($_SESSION['id']);

  $username = $user[0]['username'];
  $image = $user[0]['image'];
  $borrowedBooks = $BooksController->getBorrowedBooksByValue('user_id', $_SESSION['id'], null);
  $borrowsCount = count($borrowedBooks);
  $favoritedBooks = $BooksController->getFavoritedBooksByValue('user_id', $_SESSION['id'], null);
  $favoritesCount = count($favoritedBooks);
  ?>
  <div class="main-profile">
  <form action="api/uploadProfilePhoto.php" method="post" enctype="multipart/form-data" id="uploadProfilePhoto">
    <label>
      <img src="data:image/jpeg;base64,<?php
      $base64_image = base64_encode($image);
      if ($base64_image == $image) {
        $image = $base64_image;
      } else {
        $image = $image;
      }
      echo $image;
      ?>" alt="profile image" class="profile-image">
      <input type="file" name="image" id="image" style="display: none;">
    </label>
  </form>
  <script>
    document.querySelector("#uploadProfilePhoto").addEventListener("change", function(event) {
      setTimeout(function() {
        document.querySelector("#uploadProfilePhoto").submit();
      }, 100);
    });
  </script>
    <div>
      <h1 class="profile-username"><?= $username ?></h1>
      <p id="rank"></p>
    </div>
    <script>
      function calculateRank() {
        const timeReading = localStorage.getItem('timeReading');
        if (timeReading < 7200) {
          return "📚 Pemula";
        }
        if (timeReading >= 7200 && timeReading < 14400) {
          return "📘 Pionir";
        }
        if (timeReading >= 14400 && timeReading < 21600) {
          return "📖 Pelajar";
        }
        if (timeReading >= 21600 && timeReading < 28800) {
          return "👞 Penjelajah";
        }
        if (timeReading >= 28800 && timeReading < 36000) {
          return "🔍 Pemikir";
        }
        if (timeReading >= 36000 && timeReading < 43200) {
          return "🌟 Visioner";
        }
        if (timeReading >= 43200 && timeReading < 50400) {
          return "🔬 Ahli";
        }
        if (timeReading >= 50400 && timeReading < 57600) {
          return "🎓 Filosof";
        }
        if (timeReading >= 57600 && timeReading < 64800) {
          return "🧠 Cendekiawan";
        }
        if (timeReading >= 64800) {
          return "🔥 Maestro";
        }
      }
      document.querySelector('#rank').innerHTML = calculateRank();
    </script>
  </div>
  <br><br><br>
  <div class="profile-cards">
    <div class="profile-card">
      <h5 style="text-align: center;">Buku Dipinjam:</h5>
      <br>
      <span><h3 style="margin-right: 4px;"><?= $borrowsCount ?></h3><img src="assets/open-book.svg" alt="book"></span>
    </div>
    <div class="profile-card">
      <h5 style="text-align: center;">Buku Difavoritkan:</h5>
      <br>
      <span><h3 style="margin-right: 4px;"><?= $favoritesCount ?></h3><img src="assets/heart-full.svg" alt="heart"></span>
    </div>
    <div class="profile-card">
      <h5 style="text-align: center;">Waktu Membaca:</h5>
      <br>
      <span><h3 id="waktu-membaca" style="margin-right: 4px;"></h3><img src="assets/clock.svg" alt="book"></span>
      <script>
        let timeReading = localStorage.getItem('timeReading');
        let hours = Math.floor(timeReading / 3600);
        let minutes = Math.floor((timeReading % 3600) / 60);
        let seconds = Math.floor((timeReading % 3600) % 60);
        document.querySelector('#waktu-membaca').innerHTML = `${hours} Jam ${minutes} Menit ${seconds} Detik`;
      </script>
    </div>
  </div>
  <br><br><br>
  <h1>Daftar Buku Dipinjam</h1>
  <br><br>
  <table id="borrows-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Judul Buku</th>
        <th>Penulis</th>
      </tr>
    </thead>
    <tbody id="borrows-body">
    </tbody>
  </table>
  <div id="borrows-pagination">
    <button id="borrows-prev-btn">Previous</button>
    <button id="borrows-next-btn">Next</button>
  </div>
  <script>
  const itemsPerPage = 5;

  const borrows = [
    <?php
    foreach ($borrowedBooks as $book) {
      $data = $BooksController->getBooksByValue('id', $book['book_id'], null);
      echo "
        {
          id: '" . $data[0]['id'] . "',
          title: '" . $data[0]['title'] . "',
          author: '" . $data[0]['author'] . "',
          cover: '" . ($data[0]['cover']) . "'
        },
      ";
    }
    ?>
  ];
  let borrowsPage = 1;

function displayBorrowsData() {
  const borrowsBody = document.getElementById('borrows-body');
  borrowsBody.innerHTML = '';

  const startIndex = (borrowsPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  const currentPageData = borrows.slice(startIndex, endIndex);

  currentPageData.forEach((item, index) => {
    const row = document.createElement('tr');
    const number = document.createElement('td');
    const imageCell = document.createElement('td');
    const image = document.createElement('img');
    const titleCell = document.createElement('td');
    const authorCell = document.createElement('td');
    const actionCell = document.createElement('td');
    const readButton = document.createElement('a');

    number.textContent = index + 1;
    image.src = "data:image/jpeg;base64," + item.cover;
    image.alt = item.title;
    titleCell.textContent = item.title;
    authorCell.textContent = item.author;
    readButton.classList.add('profile-read-button');
    readButton.href = `?page=book&id=${item.id}`;
    readButton.textContent = "Baca"

    row.appendChild(number);
    row.appendChild(imageCell);
    imageCell.appendChild(image);
    row.appendChild(titleCell);
    row.appendChild(authorCell);
    row.appendChild(actionCell);
    actionCell.appendChild(readButton);

    borrowsBody.appendChild(row);
  });
}

function updateBorrowsTable() {
  const prevButton = document.getElementById('borrows-prev-btn');
  const nextButton = document.getElementById('borrows-next-btn');

  prevButton.disabled = borrowsPage === 1;
  nextButton.disabled = borrowsPage === Math.ceil(borrows.length / itemsPerPage);
}

function borrowsGoToPreviousPage() {
  if (borrowsPage > 1) {
    borrowsPage--;
    displayBorrowsData();
    updateBorrowsTable();
  }
}

function borrowsGoToNextPage() {
  if (borrowsPage < Math.ceil(borrows.length / itemsPerPage)) {
    borrowsPage++;
    displayBorrowsData();
    updateBorrowsTable();
  }
}

document.getElementById('borrows-prev-btn').addEventListener('click', borrowsGoToPreviousPage);
document.getElementById('borrows-next-btn').addEventListener('click', borrowsGoToNextPage);

displayBorrowsData();
updateBorrowsTable();
  </script>
  <br><br>
<h1>Daftar Buku Favorit</h1>
  <br><br>
<table id="favorites-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Judul Buku</th>
        <th>Penulis</th>
      </tr>
    </thead>
    <tbody id="favorites-body">
    </tbody>
  </table>
  <div id="favorites-pagination">
    <button id="favorites-prev-btn">Previous</button>
    <button id="favorites-next-btn">Next</button>
  </div>
  <script>
  const favorites = [
    <?php
    foreach ($favoritedBooks as $book) {
      $data = $BooksController->getBooksByValue('id', $book['book_id'], null);
      echo "
        {
          id: '" . $data[0]['id'] . "',
          title: '" . $data[0]['title'] . "',
          author: '" . $data[0]['author'] . "',
          cover: '" . ($data[0]['cover']) . "'
        },
      ";
    }
    ?>
  ];
  let favoritesPage = 1;

function displayfavoritesData() {
  const favoritesBody = document.getElementById('favorites-body');
  favoritesBody.innerHTML = '';

  const startIndex = (favoritesPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  const currentPageData = favorites.slice(startIndex, endIndex);

  currentPageData.forEach((item, index) => {
    const row = document.createElement('tr');
    const number = document.createElement('td');
    const imageCell = document.createElement('td');
    const image = document.createElement('img');
    const titleCell = document.createElement('td');
    const authorCell = document.createElement('td');
    const actionCell = document.createElement('td');
    const readButton = document.createElement('a');

    number.textContent = index + 1;
    image.src = "data:image/jpeg;base64," + item.cover;
    image.alt = item.title;
    titleCell.textContent = item.title;
    authorCell.textContent = item.author;
    readButton.classList.add('profile-read-button');
    readButton.href = `?page=book&id=${item.id}`;
    readButton.textContent = "Baca"

    row.appendChild(number);
    row.appendChild(imageCell);
    imageCell.appendChild(image);
    row.appendChild(titleCell);
    row.appendChild(authorCell);
    row.appendChild(actionCell);
    actionCell.appendChild(readButton);

    favoritesBody.appendChild(row);
  });
}

function updatefavoritesTable() {
  const prevButton = document.getElementById('favorites-prev-btn');
  const nextButton = document.getElementById('favorites-next-btn');

  prevButton.disabled = favoritesPage === 1;
  nextButton.disabled = favoritesPage === Math.ceil(favorites.length / itemsPerPage);
}

function favoritesGoToPreviousPage() {
  if (favoritesPage > 1) {
    favoritesPage--;
    displayfavoritesData();
    updatefavoritesTable();
  }
}

function favoritesGoToNextPage() {
  if (favoritesPage < Math.ceil(favorites.length / itemsPerPage)) {
    favoritesPage++;
    displayfavoritesData();
    updatefavoritesTable();
  }
}

document.getElementById('favorites-prev-btn').addEventListener('click', favoritesGoToPreviousPage);
document.getElementById('favorites-next-btn').addEventListener('click', favoritesGoToNextPage);

displayfavoritesData();
updatefavoritesTable();
  </script>
</main>