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
    $queryMember = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, COALESCE(m.status, 'tidak ada') as status FROM member m LEFT OUTER JOIN paket_member p ON p.id_paket = m.id_paket;";


    // Update otomatis status keanggotaan jika tanggal_berakhir sudah jatuh tempo
    $updateStatusQuery = "UPDATE member SET status = 'tidak aktif' WHERE tanggal_berakhir < CURRENT_DATE AND status = 'aktif'";
    $conn->query($updateStatusQuery);


    // kondisi data member dengan search
    if(isset($_GET['search']))
    {
        $search = $_GET['search'];
        $queryMember = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, COALESCE(m.status, 'tidak ada') as status FROM member m LEFT OUTER JOIN paket_member p ON p.id_paket = m.id_paket WHERE m.nama_member LIKE '%$search%'";
        $resultMember = $conn->query($queryMember);
    } else if(isset($_GET['filter'])) {
        $filter = $_GET['filter'];

        if($filter === "aktif") {
            $queryMember = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, COALESCE(m.status, 'tidak ada') as status FROM member m LEFT OUTER JOIN paket_member p ON p.id_paket = m.id_paket WHERE m.status = 'aktif'";
            $resultMember = $conn->query($queryMember);
        } else if($filter === "tidak-aktif") {
            $queryMember = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, COALESCE(m.status, 'tidak ada') as status FROM member m LEFT OUTER JOIN paket_member p ON p.id_paket = m.id_paket WHERE m.status = 'tidak aktif'";
            $resultMember = $conn->query($queryMember);
        }
    }else {
        $queryMember = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, COALESCE(m.status, 'tidak ada') as status FROM member m LEFT OUTER JOIN paket_member p ON p.id_paket = m.id_paket;";
        $resultMember = $conn->query($queryMember);
    }

    // jumlah data member
    $queryAmountMember = "SELECT COUNT(id_member) As total_member FROM member";
    $resultAmountMember = $conn->query($queryAmountMember);
    $rowAmountMember = $resultAmountMember->fetch(PDO::FETCH_ASSOC);

    // jumlah data member aktif
    $queryAmountMemberActive = "SELECT COUNT(id_member) As total_member_aktif FROM member WHERE status = 'aktif'";
    $resultAmountMemberActive = $conn->query($queryAmountMemberActive);
    $rowAmountMemberActive = $resultAmountMemberActive->fetch(PDO::FETCH_ASSOC);

    // jumlah data member nonaktif
    $queryAmountMemberNonactive = "SELECT COUNT(id_member) As total_member_nonaktif FROM member WHERE status = 'tidak aktif'";
    $resultAmountMemberNonactive = $conn->query($queryAmountMemberNonactive);
    $rowAmountMemberNonactive = $resultAmountMemberNonactive->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>"">
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
                            <a href="admin.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/home.svg" alt="dashboard-nav">
                                    Beranda
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                <h3>
                    Log Out
                </h3>
            </a>
        </div>
        <div class="content">
            <header>
                <div class="container">
                    <div class="title-page">
                        <h2>Beranda</h2>
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
                <!-- amount member -->
                <section class="amount-member">
                    <!-- card amount member layout -->
                    <div class="container">
                        <!-- card member -->
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-member.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Jumlah Member</h3>
                                <h4><?= $rowAmountMember['total_member']?></h4>
                            </div>
                        </div>
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-active.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Masih Aktif</h3>
                                <h4><?= $rowAmountMemberActive['total_member_aktif']?></h4>
                            </div>
                        </div>
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-nonactive.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Tidak Aktif</h3>
                                <h4><?= $rowAmountMemberNonactive['total_member_nonaktif'] ?></h4>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- filtering member -->
                <section class="filtering-member">
                    <div class="container">
                        <!-- filter member -->
                        <form method="GET">
                            <div class="filter-member">
                                <select name="filter">
                                    <option value="filter">Filter</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak-aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </form>
                        <!-- search member -->
                        <form method="GET">
                            <div class="search-member container">
                                <input type="text" name="search" id="search" placeholder="Search" value="<?= $_GET['search'] ?? '' ?>">
                                <img src="assets/search.svg" alt="search">
                            </div>
                        </form>
                    </div>
                </section>
                <section class="member-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 20%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 15%;">Tanggal Berlaku</td>
                                <td style="text-align: center; width: 10%;">Status</td>
                                <td style="text-align: center; width: 15%;">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resultMember as $result) : ?>
                            <tr>
                            <tr>
                                <td><?= $result['nama_member']?></td>
                                <td style="text-align: center;"><?= $result['nomor_telepon']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_fasilitas']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_durasi']?></td>
                                <td style="text-align: center;"><?= $result['tanggal_berakhir']?></td>
                                <td style="text-align: center;"><?= $result['status']?></td>
                                <td>
                                    <div class="action container">
                                        <a href="admin-detail.php?id=<?=$result['id_member']; ?>" class="detail">Detail</a>
                                        <a href="delete-member.php?id=<?=$result['id_member']; ?>" class="hapus">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>
</body>
</html>