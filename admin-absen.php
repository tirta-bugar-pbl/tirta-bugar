<?php 
    session_start();
    include 'koneksi.php';

    if(!isset($_SESSION['email'])){
        header('Location: admin-login.php');
        exit();
    }

    // mengambil data profile di php
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT username FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);

    // variabel pagination
    $limit = 10; 
    $page = isset($_GET['page']) ? $_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    // Mengambil search
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // mengambil data absen
    if($search) {
        $queryAbsen = "SELECT * FROM view_member_absen_list WHERE LOWER(nama_member) LIKE '%' || LOWER('$search') || '%'";

        $resultAbsen = $conn->query($queryAbsen);

        // Hitung total absen untuk pagination
        $queryCount = "SELECT COUNT(DISTINCT id_pertemuan) AS total FROM view_member_absen_list";
        $resultCount = $conn->query($queryCount);
        $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalCount / $limit);
    }else {
        $queryAbsen = "SELECT * FROM view_member_absen_list LIMIT $limit OFFSET $offset";

        $resultAbsen = $conn->query($queryAbsen);

        // Hitung total absen untuk pagination
        $queryCount = "SELECT COUNT(DISTINCT id_pertemuan) AS total FROM absen_harian";
        $resultCount = $conn->query($queryCount);
        $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalCount / $limit);
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
    <link rel="stylesheet" href="css/admin-absen.css?v=<?php echo time(); ?>">
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
                            <a href="admin-akun.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/setting.svg" alt="setting-nav">
                                    Pengaturan Akun
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-absen.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/calendar.svg" alt="calendar-nav">
                                    Absensi Harian
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                        <h2>Absensi Harian</h2>
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
                <!-- filtering absen -->
                <section class="filtering-absen">
                    <div class="container">
                        <form method="GET">
                            <!-- search absen -->
                            <div class="search-absen container">
                                <input type="text" name="search" id="search" placeholder="Search">
                                <img src="assets/search.svg" alt="search">
                            </div>
                        </form>
                    </div>
                </section>
                <section class="absen-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 15%;">Tanggal Datang</td>
                                <td style="text-align: center; width: 20%;">nama</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Tanggal Berlaku</td>
                                <td style="text-align: center; width: 15%;">Keterangan Member</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- data table -->
                            <?php foreach ($resultAbsen as $result) : ?>
                                <tr>
                                <td style="text-align: center;"><?= $result['tanggal_datang'] ?></td>
                                <td><?= $result['nama_member'] ?></td>
                                <td style="text-align: center;"><?= $result['keterangan_durasi'] ?></td>
                                <td style="text-align: center;"><?= $result['tanggal_berakhir'] ?></td>
                                <td style="text-align: center;"><?= $result['keterangan_fasilitas'] ?></td>          
                                <td style="text-align: center;"><?= $result['keterangan_absen'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
                <!-- Pagination -->
                <section class="pagination">
                    <div class="container">
                        <ul>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li><a href="?page=<?= $i ?>&search=<?= $search ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>