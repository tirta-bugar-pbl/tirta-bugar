<?php 
    session_start();
    include 'koneksi.php';

    if(!isset($_SESSION['email'])){
        header('Location: admin-login.php');
        exit();
    }

    // mengambil data profile di php // mengambil data profile di php
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT id_admin, username, email FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);


    // update profile
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $email = $_POST['email'];

        if(empty($username) || empty($email)){
            echo "<script>alert('Wajib isi Form !');</script>";
        } else {
            $queryUpdateProfile = "UPDATE admin SET username = '$username', email = '$email' WHERE id_admin = $adminId";
            $resultUpdateProfile = $conn->query($queryUpdateProfile);
            if($resultUpdateProfile){
                header('Location: admin-akun.php');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-tambah.css?v=<?php echo time(); ?>">
     <!-- link favicon -->
     <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="notifications.js"></script>

</head>
<body>
    <div class="container">
        <!-- sidebar -->
        <div class="sidebar container">
            <!-- sidebar layout -->
            <div class="navbar-menu">
                <!-- sidebar logo -->
                <div class="logo-sidebar">
                    <h1>TB</h1>
                </div>
                <!-- sidebar menu -->
                <nav>
                    <ul>
                        <li>
                            <a href="admin.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/home.svg" alt="dashboard-nav">
                                    Beranda
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-tambah.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/plus.svg" alt="tambah-nav">
                                    Tambah Member
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-paket.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/note.svg" alt="paket-nav">
                                    Daftar Paket
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-transaksi.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/transaction.svg" alt="transaction-nav">
                                    Transaksi
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-akun.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/setting.svg" alt="setting-nav">
                                    Pengaturan Akun
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
                            </a>
                        </li>
                        <li>
                            <a href="admin-absen.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/calendar.svg" alt="calendar-nav">
                                    Absensi Harian
                                </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- sidebar log out -->
            <a href="logout.php" class="log-out container">
                <img src="assets/log-out.svg" alt="log-out">
                <h3>Log Out</h3>
            </a>
        </div>
        <div class="content">
            <header>
                <div class="container">
                    <div class="title-page">
                        <h2>Edit Member</h2>
                    </div>
                <div class="account">
            <!-- notif account -->
            <div id="notification-container" class="notification-container">
                <div class="notification-icon-wrapper">
                    <img src="assets/notification.svg" alt="notification" id="notificationIcon">
                    <span class="notification-badge hidden"></span>
                </div>
            </div>
            <div class="account-profile">
                <!-- icon account -->
                <img src="assets/profile.svg" alt="profile">
                <h3><?= $rowProfileName['username']?></h3>
            </div>
        </div>
            </div>
                </header>
        
            <!-- Pop-Up Notification -->
        <div id="notification-popup" class="popup hidden">
            <div class="popup-content">
                <span id="close-popup" class="close">&times;</span>
                <ul id="notification-list"></ul>
            </div>
        </div>
            <main>
                <!-- form tambah member -->
                <section class="tambah-member">
                    <form class="form-tambah container" method="POST">
                        <div class="form-group container">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="<?= $rowProfileName['username']?>" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?= $rowProfileName['email']?>" class="input-tambah">
                        </div>
                        <div class="btn-group container">
                            <button type="submit" name="submit" class="btn-tambah">Edit Akun</button>
                            <button class="btn-cancell">Batalkan</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>