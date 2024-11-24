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

    // mengambil data detail member
    $id = $_GET['id'];
    $queryDetailMember = "SELECT nama_member, email, nomor_telepon, COALESCE(no_kwitansi, 'belum diupdate') as no_kwitansi, status, tanggal_berakhir, id_paket FROM member WHERE id_member = $id";
    $resultDetailMember = $conn->query($queryDetailMember);
    $rowDetailMember = $resultDetailMember->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['submit'])) {
        $nama_member = $_POST['nama'];
        $email = $_POST['email'];
        $durasi = $_POST['durasi'];
        $nomor_telepon = $_POST['nomor-telepon'];
        $no_kwitansi = $_POST['no-kwitansi'];
        $tanggal_berakhir = $_POST['tanggal-akhir'];

        if (!preg_match("/^[0-9]+$/", $nomor_telepon)) {
            echo "<script>alert('Nomor telepon harus berupa angka !');</script>";
        } else if (!is_numeric($no_kwitansi)) {
            echo "<script>alert('Nomor kwitansi harus berupa angka !');</script>";
        } else {
            $queryEdit = "UPDATE member SET nama_member = '$nama_member', email = '$email', no_kwitansi = '$no_kwitansi', nomor_telepon = '$nomor_telepon', tanggal_berakhir = '$tanggal_berakhir', id_paket = '$durasi', status = 'aktif' WHERE id_member = $id";

            if ($conn->query($queryEdit)) {
                header("Location: admin.php");
            } else {
                echo "Error: " . $queryEdit . "<br>" . $conn->error;
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
                <!-- form tambah member -->
                <section class="tambah-member">
                    <form class="form-tambah container" method="POST">
                        <div class="form-group container">
                            <label for="nama">Nama (Sesuai KTP)</label>
                            <input type="text" name="nama" id="nama" value="<?= $rowDetailMember['nama_member'] ?>" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?= $rowDetailMember['email'] ?>" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="nomor-telepon">Nomor Telepon</label>
                            <input type="text" name="nomor-telepon" value="<?= $rowDetailMember['nomor_telepon'] ?>" id="nomor-telepon" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="durasi">Pilihan Paket</label>
                            <select name="durasi" id="durasi" class="input-tambah">
                                <option value="1" <?php if($rowDetailMember['id_paket'] == 1) echo "selected"?>>Regullar - 1 Bulan 8x Fitness</option>
                                <option value="2" <?php if($rowDetailMember['id_paket'] == 2) echo "selected"?>>Regullar - 1 Bulan Fitness Sepuasnya</option>
                                <option value="3" <?php if($rowDetailMember['id_paket'] == 3) echo "selected"?>>Regullar - 3 Bulan Fitness Sepuasnya</option>
                                <option value="4" <?php if($rowDetailMember['id_paket'] == 3) echo "selected"?>>Regullar - 1 Bulan Sepuasnya + 4x Private Fitness</option>
                            </select>
                        </div>
                        <div class="form-group container">
                            <label for="tanggal-awal">Tanggal Perpanjangan</label>
                            <input type="date" name="tanggal-awal" id="tanggal-awal" value="<?= date('Y-m-d') ?>" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="tanggal-akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal-akhir" id="tanggal-akhir" value="<?= $rowDetailMember['tanggal_berakhir'] ?>" class="input-tambah">
                        </div>
                        <div class="form-group container">
                            <label for="no-kwitansi">No Kwitansi</label>
                            <input type="text" name="no-kwitansi" id="no-kwitansi" value="<?= $rowDetailMember['no_kwitansi'] ?>" class="input-tambah">
                        </div>
                        <div class="btn-group container">
                            <button type="submit" name="submit" class="btn-tambah">Edit Member</button>
                            <a href="admin-detail.php?id=<?= $id ?>" class="btn-cancell">Batalkan</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>