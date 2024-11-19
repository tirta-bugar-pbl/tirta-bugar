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

    // Eksekusi query
    // $resultTransaksi = $conn->query($queryTransaksi);

    // Mengambil nilai filter dan search dari URL
    // $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Menyusun query berdasarkan filter status dan search
    // $queryTransaksi = "SELECT DISTINCT TO_CHAR(t.tanggal_transaksi, 'DD Month YYYY') AS tanggal_transaksi, m.nama_member, m.nomor_telepon, t.id_paket, p.id_paket, p.keterangan_fasilitas, p.keterangan_durasi, m.id_member, t.id_member, t.status_pembayaran, t.total_harga FROM member m JOIN transaksi t ON m.id_member = t.id_member JOIN paket_member p ON t.id_paket = p.id_paket 
    // WHERE 1=1";

    // Menambahkan kondisi filter status jika ada
    // if ($filter == 'aktif') {
    //     $queryTransaksi .= " AND m.status = 'aktif'";
    // } else if ($filter == 'tidak-aktif') {
    //     $queryTransaksi .= " AND (m.status != 'aktif' OR m.status IS NULL)";
    // }

    // Menambahkan kondisi pencarian nama_member jika ada
    if (!empty($search)) {
        $queryTransaksi = "SELECT DISTINCT TO_CHAR(t.tanggal_transaksi, 'DD Month YYYY') AS tanggal_transaksi, m.nama_member, m.nomor_telepon, p.keterangan_fasilitas, p.keterangan_durasi, t.status_pembayaran, 
t.total_harga FROM member m JOIN transaksi t ON m.id_member = t.id_member JOIN paket_member p ON t.id_paket = p.id_paket WHERE m.nama_member LIKE '%$search%'";

        // Eksekusi query
        $resultTransaksi = $conn->query($queryTransaksi);

        // Hitung total transaksi untuk pagination
        $queryCount = "SELECT COUNT(DISTINCT t.id_transaksi) AS total FROM transaksi t JOIN member m ON m.id_member = t.id_member";
        $resultCount = $conn->query($queryCount);
        $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalCount / $limit);
    } else {
        // Tambahkan LIMIT dan OFFSET untuk pagination
        $queryTransaksi = "SELECT DISTINCT TO_CHAR(t.tanggal_transaksi, 'DD Month YYYY') AS tanggal_transaksi, m.nama_member, m.nomor_telepon, p.keterangan_fasilitas, p.keterangan_durasi, t.status_pembayaran, 
t.total_harga FROM member m JOIN transaksi t ON m.id_member = t.id_member JOIN paket_member p ON t.id_paket = p.id_paket LIMIT $limit OFFSET $offset";

        // Eksekusi query
        $resultTransaksi = $conn->query($queryTransaksi);

        // Hitung total transaksi untuk pagination
        $queryCount = "SELECT COUNT(DISTINCT t.id_transaksi) AS total FROM transaksi t JOIN member m ON m.id_member = t.id_member";
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
    <link rel="stylesheet" href="css/admin-transaksi.css?v=<?php echo time(); ?>">
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
                            <select name="filter">
                                <option value="">Filter</option>
                                <option value="today">Hari Ini</option>
                                <option value="week">Minggu Ini</option>
                                <option value="month">Bulan ini</option>
                            </select>
                        </div>
                        <!-- search member -->
                        <div class="search-transaksi container">
                            <input type="text" name="search" id="search" placeholder="Search">
                            <img src="assets/search.svg" alt="search">
                        </div>
                    </form>
                </section>
                <section class="member-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 15%;">Tanggal Transaksi</td>
                                <td style="text-align: center;width: 15%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 15%;">Status Pembayaran</td>
                                <td style="text-align: center; width: 15%;">Total Bayar</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultTransaksi as $result) : ?>
                                <tr>
                                <td style="text-align: center;"><?= $result['tanggal_transaksi']?></td>
                                <td><?= $result['nama_member']?></td>
                                <td style="text-align: center;"><?= $result['nomor_telepon']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_fasilitas']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_durasi']?></td>
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
</body>
</html>