<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['email'])) {
        die("Anda belum login.");
    }

    // Cek ID Paket
    if (!isset($_GET['id_paket']) || !is_numeric($_GET['id_paket']) || empty($_GET['id_paket'])) {
        die("ID Paket tidak ditemukan atau tidak valid.");
    }

    $id_paket = (int)$_GET['id_paket'];

    // Ambil data paket
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT username FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);

    $queryPaket = "SELECT * FROM paket_member WHERE id_paket = $id_paket";
    $paket = $conn->query($queryPaket);
    $resultPaket = $paket->fetch(PDO::FETCH_ASSOC);

    if (!$paket) {
        die("Data paket tidak ditemukan.");
    }

    // Proses update data
    if (isset($_POST['submit'])) {
        $nama_paket = $_POST['nama_paket'];
        $keterangan_fasilitas = $_POST['keterangan_fasilitas'];
        $keterangan_durasi = $_POST['keterangan_durasi'];
        $keterangan_private = $_POST['keterangan_private'];
        $harga = (int)$_POST['harga'];


        if (empty($keterangan_private)) {
            // $queryUpdate = "UPDATE paket_member SET nama_paket = '$nama_paket', keterangan_fasilitas = '$keterangan_fasilitas', keterangan_durasi = '$keterangan_durasi', harga = '$harga', keterangan_private = null, id_admin = '$adminId' WHERE id_paket = '$id_paket'";
            $queryUpdate = "CALL edit_paket_member('$id_paket', '$nama_paket', '$keterangan_fasilitas', '$keterangan_durasi', '$harga', null, '$adminId')";
        } else {
            // $queryUpdate = "UPDATE paket_member SET nama_paket = '$nama_paket', keterangan_fasilitas = '$keterangan_fasilitas', keterangan_durasi = '$keterangan_durasi', harga = '$harga', keterangan_private = '$keterangan_private', id_admin = '$adminId' WHERE id_paket = '$id_paket'";
            $queryUpdate = "CALL edit_paket_member('$id_paket', '$nama_paket', '$keterangan_fasilitas', '$keterangan_durasi', '$harga', '$keterangan_private', '$adminId')";
        }

        if ($conn->query($queryUpdate)) {
            header('Location: admin-paket.php');
            exit();
        } else {
            die("Gagal menyimpan perubahan.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket</title>
    <!-- Link CSS -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-edit-paket.css?v=<?php echo time(); ?>">
    <!-- Link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- Link Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src= notifications.js></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar container">
            <div class="navbar-menu">
                <div class="logo-sidebar">
                    <h1>TB</h1>
                </div>
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
            <a href="logout.php" class="log-out container">
                <img src="assets/log-out.svg" alt="log-out">
                <h3>Log Out</h3>
            </a>
        </div>
        
        <div class="content">
            <header>
                <div class="container">
                    <div class="title-page">
                        <h2>Edit Paket</h2>
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
                <!-- form edit paket -->
                <section class="edit-paket">
                    <form method="POST" class="form-edit container">
                        <input type="hidden" name="id_paket" value="<?= $resultPaket['id_paket'] ?>">

                        <div class="form-group container">
                            <label for="nama_paket">Nama Paket</label>
                            <input type="text" name="nama_paket" id="nama_paket" class="input-edit" value="<?= $resultPaket['nama_paket'] ?>" required>
                        </div>
                        
                        <div class="form-group container">
                            <label for="keterangan_fasilitas">Keterangan Fasilitas</label>
                            <input type="text" name="keterangan_fasilitas" id="keterangan_fasilitas" class="input-edit" value="<?= $resultPaket['keterangan_fasilitas'] ?>" required>
                        </div>

                        <div class="form-group container">
                            <label for="keterangan_durasi">Keterangan Durasi (berapa pertemuan)</label>
                            <input type="text" name="keterangan_durasi" id="keterangan_durasi" class="input-edit" value="<?= $resultPaket['keterangan_durasi'] ?>" required>
                        </div>

                        
                        <div class="form-group container">
                            <label for="keterangan_private">Keterangan Private (berapa pertemuan)</label>
                            <input type="text" name="keterangan_private" id="keterangan_private" class="input-edit" value="<?= $resultPaket['keterangan_private'] ?>">
                        </div>

                        <div class="form-group container">
                            <label for="harga">Harga</label>
                            <input type="text" name="harga" id="harga" class="input-edit" value="<?= $resultPaket['harga'] ?>" required>
                        </div>

                        <div class="btn-group container">
                            <button type="submit" name="submit" class="btn-edit">Simpan</button>
                            <a href="admin-paket.php" class="btn-cancell">Kembali</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
