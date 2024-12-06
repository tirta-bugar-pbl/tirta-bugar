<?php 
    session_start();
    include 'koneksi.php';

    if(!isset($_SESSION['email'])){
        header('Location: admin-login.php');
        exit();
    }

    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    // mengambil data profile di php
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT username FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);

    // logika pagination
    $limit = 10; // Example limit per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default to 1
    $offset = ($page - 1) * $limit; // Offset for SQL query 

    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $queryTransaksi = "SELECT * FROM view_member_transaction_list WHERE 1=1";

    
    // Hitung total transaksi untuk pagination
    $queryCount = "SELECT COUNT(DISTINCT id_transaksi) AS total FROM view_member_transaction_list";
    $resultCount = $conn->query($queryCount);
    $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalCount / $limit);

    // kondisi searching 
    if (!empty($search)) {
        $queryTransaksi .= " AND LOWER(nama_member) LIKE '%' || LOWER('$search') || '%'";
    } 

    // Tambahkan filter waktu
    if ($filter == 'today') {
        $queryTransaksi .= " AND DATE(tanggal_transaksi) = CURRENT_DATE";
    } else if ($filter == 'week') {
        $queryTransaksi .= " AND DATE(tanggal_transaksi) >= (CURRENT_DATE - INTERVAL '7 days')";
    } else if ($filter == 'month') {
        $queryTransaksi .= " AND DATE_PART('month', tanggal_transaksi) = DATE_PART('month', CURRENT_DATE) AND DATE_PART('year', tanggal_transaksi) = DATE_PART('year', CURRENT_DATE)";
    }

    // Tambahkan LIMIT dan OFFSET untuk pagination
    $queryTransaksi .= " LIMIT $limit OFFSET $offset";

    // Eksekusi query
    $resultTransaksi = $conn->query($queryTransaksi);

    // Hitung total transaksi untuk pagination
    $queryCount = "SELECT COUNT(DISTINCT id_transaksi) AS total FROM view_member_transaction_list";
    $resultCount = $conn->query($queryCount);
    $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalCount / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-transaksi.css?v=<?php echo time(); ?>">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
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
                            <a href="admin-transaksi.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/transaction.svg" alt="transaction-nav">
                                    Transaksi
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                        <h2>Transaksi</h2>
                    </div>
                    <div class="account">
                        <!-- notif account -->
                        <img src="assets/notification.svg" alt="notifivation">
                        <div class="account-profile">
                            <!-- icon account -->
                            <img src="assets/profile.svg" alt="profile">
                            <h3><?= $rowProfileName['username']?></h3>
                        </div>
                    </div>
                </div>
            </header>
            <main>
                <!-- filtering transaksi -->
                <section class="filtering-transaksi">
                    <form method="GET" class="container">
                        <!-- filter member -->
                        <div class="filter-transaksi">
                            <select name="filter" id=filter onchange="applyFilter()">
                                <option value="">Filter</option>
                                <option value="today" <?= isset($_GET['filter']) && $_GET['filter'] === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                                <option value="week" <?= isset($_GET['filter']) && $_GET['filter'] === 'week' ? 'selected' : '' ?>>Minggu Ini</option>
                                <option value="month" <?= isset($_GET['filter']) && $_GET['filter'] === 'month' ? 'selected' : '' ?>>Bulan ini</option>
                            </select>
                        </div>
                        <!-- search member -->
                        <div class="search-transaksi container">
        <input type="text" name="search" id="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>">
        <img src="assets/search.svg" alt="search">
    </div>
                </section>
                <section class="member-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 15%;">Tanggal Transaksi</td>
                                <td style="text-align: center;width: 10%;">Invoice</td>
                                <td style="text-align: center;width: 15%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 10%;">Status Pembayaran</td>
                                <td style="text-align: center; width: 15%;">Total Bayar</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultTransaksi as $result) : ?>
                                <tr>
                                <td style="text-align: center;"><?= $result['tanggal_transaksi_formated']?></td>
                                <td style="text-align: center;"><?= $result['invoice']?></td>
                                <td><?= $result['nama_member']?></td>
                                <td style="text-align: center;"><?= $result['nomor_telepon']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_durasi']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_fasilitas']?></td>
                                <td style="text-align: center;"><b><?= $result['status_pembayaran']?></b></td>
                                <td style="text-align: center;"><?= rupiah($result['total_harga'])?></td>
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
    <script>
        function applyFilter() {
            const filter = document.getElementById('filter').value;
            const url = new URL(window.location.href);
            url.searchParams.set('filter', filter);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>