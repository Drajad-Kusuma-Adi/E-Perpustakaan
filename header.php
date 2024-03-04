<header class="navbar">
    <a href="./" style="text-decoration: none; color: #000; margin: 24px;">
        <div>
            <img src="assets/logo.svg" alt="logo">
            <h1>E-Perpustakaan</h1>
        </div>
    </a>
    <div>
    <form action="" method="post" style="margin-left: 24px;">
        <input type="text" name="search" class="search" id="search" placeholder="Cari Buku">
        <label style="padding: 0; display: flex; justify-content: center; align-items: center;">
            <img src="assets/search.svg" alt="search" class="search-button">
            <input type="submit" value="Search" hidden>
        </label>
    </form>
    <script>
        let dropdownOpen;
    </script>
    <?php
        if (isset($_SESSION['level'])) {
            switch ($_SESSION['level']) {
                case 2: ?>
                    <div class="dropdown">
                        <div>
                            <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['image']) . '" width="200" height="300"/>' ?>
                            <button onclick="openDropdown()" class="dropdown-button" id="dropdown-button"><img src="assets/arrow.svg" alt="arrow"></button>
                        </div>
                        <ul id="dropdown-content" style="margin-top: 240px;" class="dropdown-content hidden">
                            <li><a href="./?page=profile">Profile <img src="assets/profile.svg" alt="profile" style="width: 16px; height: 16px;"></a></li>
                            <li><a href="./?page=account">Accounts <img src="assets/account.svg" alt="accounts" style="width: 16px; height: 16px;"></a></li>
                            <li><a href="api/logout.php">Logout <img src="assets/logout.svg" alt="logout" style="width: 16px; height: 16px;"></a></li>
                        </ul>
                    </div>
                    <script>
                        dropdownOpen = false;
                        window.onclick = function(event) {
                            if (dropdownOpen === true) {
                                document.querySelector('#dropdown-content').classList.add('hidden');
                                dropdownOpen = false;
                            }
                        }
                        function openDropdown() {
                            document.querySelector('#dropdown-content').classList.remove('hidden');
                            setTimeout(function() {
                                dropdownOpen = true;
                            }, 10);
                        };
                    </script>
                <?php
                break;
                case 1: ?>
                    <div class="dropdown" style="margin: 24px;">
                        <div>
                            <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['image']) . '" width="200" height="300"/>' ?>
                            <button onclick="openDropdown()" class="dropdown-button" id="dropdown-button"><img src="assets/arrow.svg" alt="arrow"></button>
                        </div>
                        <ul id="dropdown-content" style="margin-top: 160px;" class="dropdown-content hidden">
                            <li><a href="./?page=profile">Profile <img src="assets/profile.svg" alt="profile" style="width: 16px; height: 16px;"></a></li>
                            <li><a href="api/logout.php">Logout <img src="assets/logout.svg" alt="logout" style="width: 16px; height: 16px;"></a></li>
                        </ul>
                    </div>
                    <script>
                        dropdownOpen = false;
                        window.onclick = function(event) {
                            if (dropdownOpen === true) {
                                document.querySelector('#dropdown-content').classList.add('hidden');
                                dropdownOpen = false;
                            }
                        }
                        function openDropdown() {
                            document.querySelector('#dropdown-content').classList.remove('hidden');
                            setTimeout(function() {
                                dropdownOpen = true;
                            }, 10);
                        };
                    </script>
                <?php
                break;
            }
        } else {
            echo ("<button id='loginButton' style='margin: 24px;' class='login'>Login</button>");
        } ?>
    </div>
</header>

<div class="login-form hidden" id="loginForm">
    <form action="api/login.php" method="post" id="loginPopup">
        <img src="assets/logo.svg" alt="logo" width="48" style="align-self: center;">
        <h2 style="align-self: center; font-weight: 400;">E-Perpustakaan</h2>
        <br><hr><br>
        <label for="username">Username</label>
        <div class="form-group">
            <img src="assets/mail.svg" alt="mail" width="16">
            <input class="form-input" type="text" name="username" id="username" placeholder="Masukkan username...">
        </div>
        <label for="password">Password</label>
        <div class="form-group">
        <img src="assets/lock.svg" alt="mail" width="16">
            <input class="form-input" type="password" name="password" id="password" placeholder="Masukkan password...">
        </div>
        <div style="display: flex; justify-content: center;">
            <input class="form-button login" type="submit" value="Login">
            <input class="form-button reset" type="reset" value="Reset">
        </div>
    </form>
</div>

<script>
    document.querySelector('#loginButton').addEventListener('click', function() {
        document.querySelector('#loginForm').classList.remove('hidden');
    });

    window.onclick = function(event) {
        if (event.target == document.querySelector('#loginForm')) {
            document.querySelector('#loginForm').classList.add('hidden');
        }
    }
</script>