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

    // mengambil data paket
    $queryTampilPaket = "SELECT id_paket, nama_paket, keterangan_fasilitas, keterangan_durasi, 'Rp ' || TO_CHAR(harga, 'FM999,999,999') as harga FROM paket_member";
    $resultTampilPaket = $conn->query($queryTampilPaket);
    $rowTampilPaket = $resultTampilPaket->fetchAll(PDO::FETCH_ASSOC);

  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-packet.css?v=<?php echo time(); ?>">
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
                            <a href="admin-paket.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/note.svg" alt="paket-nav">
                                    Daftar Paket
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                <h3>Log Out</h3>
            </a>
        </div>
        <div class="content">
            <header>
                <div class="container">
                    <div class="title-page">
                        <h2>Daftar Paket</h2>
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
                <!-- paket list -->
                <section class="packet-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td>Nama Paket</td>
                                <td>Harga</td>
                                <td>Durasi</td>
                                <td>Keterangan Fasilitas</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rowTampilPaket as $result) : ?>
                            <tr>
                                <td><?= $result['nama_paket']?></td>
                                <td><?= $result['harga']?></td>
                                <td><?= $result['keterangan_durasi']?></td>
                                <td><?= $result['keterangan_fasilitas']?></td>
                                <td>
       
          <!-- Tombol Edit -->
<form action="edit-paket.php" method="GET" style="display: inline;">
    <input type="hidden" name="id_paket" value="<?= $result['id_paket'] ?>">
    <button type="submit" class="btn-edit">Edit</button>
</form>
            <!-- Tombol Hapus -->
            <form action="hapus-paket.php" method="POST" style="display: inline;">
                <input type="hidden" name="id_paket" value="<?= $result['id_paket'] ?>">
                <button type="submit" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">Hapus</button>
            </form>
        </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align: right; margin-top: 10px;">
    <a href="tambah-paket.php" class="button">Tambah Paket</a>
</div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>