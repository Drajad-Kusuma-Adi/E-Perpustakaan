<main>
  <?php require "api/config.php" ?>
  <?php require "api/UsersController.php" ?>
  <?php
  $controller = new UsersController($conn);
  $users = $controller->getAllUserData(null);
  $userCount = count($users);
  if(isset($_POST['state'])) {
    $controller->createUser($_POST['username'], $_POST['password'], $_POST['level']);
    echo "<script>window.location.href = '/e-perpustakaan/?page=account'</script>";
    // header("Location: /e-perpustakaan?success=1");//create redirect not working
  } elseif(isset($_POST['username'])) {
    $controller->updateUser($_POST['id'], $_POST['username'], $_POST['password'], $_POST['level']);
    echo "<script>window.location.href = '/e-perpustakaan/?page=account'</script>";
    // header("Location: /e-perpustakaan?success=1");//update redirect not working
  }
  if(isset($_GET['del'])) {
    $controller->deleteUser($_GET['id']);
    echo "<script>window.location.href = '/e-perpustakaan/?page=account'</script>";
    // header("Location: /e-perpustakaan?success=1");//delete redirect not working
  }
  if(isset($_GET['upd'])) {
    $id=$controller->getUserDataById($_GET['id']);
    ?>
    <div class="create-form" id="createForm">
      <form method="post">
        <img src="assets/logo.svg" alt="logo" width="48" style="align-self: center;">
        <h2 style="align-self: center; font-weight: 400;">E-Perpustakaan</h2>
        <br><hr><br>
        <!-- <input type="text" name="state" value="book" hidden> -->
        <input type="hidden" name="id" value="<?=$id[0]['id']?>">
        <label for="username">Username</label>
        <div class="form-group">
          <input class="form-input" type="text" value="<?=$id[0]['username']?>" name="username" id="Username" placeholder="Masukkan username...">
        </div>
        <label for="password">Password</label>
        <div class="form-group">
          <input class="form-input" type="text" value="<?=$id[0]['password']?>" name="password" id="Password" placeholder="Masukkan password...">
        </div>
        <label for="level">Level</label>
        <div class="form-group">
          <input class="form-input" type="number" value="<?=$id[0]['level']?>" min="1" max="2" name="level" id="Level" placeholder="Masukkan level...">
        </div>
        <div style="display: flex; justify-content: center;">
          <input class="form-button create" type="submit" value="Update">
          <a href="?page=account" style="text-decoration: none" class="form-button cancel">Cancel</a>
        </div>
      </form>
    </div>
  <?php } ?>
  <h1>Daftar Akun</h1>
  <br>
  <table id="borrows-table">
    <thead>
      <tr>
        <th>No</th>
        <th>Username</th>
        <th>Password</th>
        <th>Akses/Level</th>
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
      foreach ($users as $user) {
        $data = $controller->getUserDataById($user['id']);
        $level = $data[0]['level'] == 2 ? "Pustakawan" : "Pengguna";
        echo "
          {
            id: '" . $data[0]['id'] . "',
            username: '" . $data[0]['username'] . "',
            password: '" . $data[0]['password'] . "',
            level: '" . $level . "'
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
        const titleCell = document.createElement('td');
        const authorCell = document.createElement('td');
        const actionCell = document.createElement('td');
        const readButton = document.createElement('a');
        const delButton = document.createElement('a');

        number.textContent = index + 1;
        imageCell.textContent = item.username;
        titleCell.textContent = item.password;
        authorCell.textContent = item.level;
        readButton.classList.add('profile-read-button');
        readButton.href = `?page=account&upd&id=${item.id}`;
        readButton.textContent = "Update"
        delButton.classList.add('profile-return-button');
        delButton.href = `?page=account&del&id=${item.id}`;
        delButton.textContent = "Delete"

        row.appendChild(number);
        row.appendChild(imageCell);
        row.appendChild(titleCell);
        row.appendChild(authorCell);
        row.appendChild(actionCell);
        actionCell.appendChild(readButton);
        actionCell.appendChild(delButton);

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
  <br><hr><br>
  <h1 style="text-align: center;">Tambahkan Akun</h1>
  <br>
  <div style="width:100%; display: flex; justify-content: center; align-items: center;">
    <form class="create-user" method="post">
      <input type="text" name="state" value="user" hidden>
      <label for="username">Username</label>
      <div class="form-group">
        <input class="form-input" type="text" style="width:100%" name="username" id="Username" placeholder="Masukkan username...">
      </div>
      <label for="password">Password</label>
      <div class="form-group">
        <input class="form-input" type="text" style="width:100%" name="password" id="Password" placeholder="Masukkan password...">
      </div>
      <label for="level">Level</label>
      <div class="form-group">
        <input class="form-input" type="number" style="width:100%" value="1" min="1" max="2" name="level" id="Level" placeholder="Masukkan level...">
      </div>
      <div style="display: flex; justify-content: center; margin-top: 10px">
        <input class="form-button create" type="submit" value="Create">
      </div>
    </form>
  </div>
</main>