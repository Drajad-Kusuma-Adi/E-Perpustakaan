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
    <img src="data:image/jpeg;base64,<?= base64_encode($image) ?>" alt="profile image" class="profile-image">
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
  <table id="data-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Judul Buku</th>
        <th>Penulis</th>
      </tr>
    </thead>
    <tbody id="table-body">
    </tbody>
  </table>
  <div id="pagination">
    <button id="prev-btn">Previous</button>
    <button id="next-btn">Next</button>
  </div>
  <script>
  const data = [
    <?php
    foreach ($borrowedBooks as $book) {
      $data = $BooksController->getBooksByValue('id', $book['book_id'], null);
      echo "
        {
          id: '" . $data[0]['id'] . "',
          title: '" . $data[0]['title'] . "',
          author: '" . $data[0]['author'] . "',
          cover: '" . base64_encode($data[0]['cover']) . "'
        }
      ";
    }
    ?>
  ];

const itemsPerPage = 5;
let currentPage = 1;

function displayTableData() {
  const tableBody = document.getElementById('table-body');
  tableBody.innerHTML = '';

  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  const currentPageData = data.slice(startIndex, endIndex);

  currentPageData.forEach((item, index) => {
    const row = document.createElement('tr');
    const number = document.createElement('td');
    const imageCell = document.createElement('td');
    const image = document.createElement('img');
    const titleCell = document.createElement('td');
    const authorCell = document.createElement('td');
    const actionCell = document.createElement('td');
    const readButton = document.createElement('a');
    const returnButton = document.createElement('button');

    number.textContent = index + 1;
    image.src = "data:image/jpeg;base64," + item.cover;
    image.alt = item.title;
    titleCell.textContent = item.title;
    authorCell.textContent = item.author;
    readButton.href = `?page=book?id=${item.id}`;
    readButton.textContent = "Baca"
    returnButton.textContent = "Kembalikan"

    row.appendChild(number);
    row.appendChild(imageCell);
    imageCell.appendChild(image);
    row.appendChild(titleCell);
    row.appendChild(authorCell);
    row.appendChild(actionCell);
    actionCell.appendChild(readButton);
    actionCell.appendChild(returnButton);

    tableBody.appendChild(row);
  });
}

function updatePaginationButtons() {
  const prevButton = document.getElementById('prev-btn');
  const nextButton = document.getElementById('next-btn');

  prevButton.disabled = currentPage === 1;
  nextButton.disabled = currentPage === Math.ceil(data.length / itemsPerPage);
}

function goToPreviousPage() {
  if (currentPage > 1) {
    currentPage--;
    displayTableData();
    updatePaginationButtons();
  }
}

function goToNextPage() {
  if (currentPage < Math.ceil(data.length / itemsPerPage)) {
    currentPage++;
    displayTableData();
    updatePaginationButtons();
  }
}

// Event listeners for pagination buttons
document.getElementById('prev-btn').addEventListener('click', goToPreviousPage);
document.getElementById('next-btn').addEventListener('click', goToNextPage);

// Initial display
displayTableData();
updatePaginationButtons();
  </script>
</main>