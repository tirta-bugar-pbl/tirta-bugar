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

    if(isset($_POST['submit'])){
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $telepon = $_POST['nomor-telepon'];
        $durasi = $_POST['durasi'];
        $tanggalAwal = $_POST['tanggal-awal'];
        $tanggalAkhir = $_POST['tanggal-akhir'];
        
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        $pwHash = base64_encode($password); 

        if (!preg_match("/^[0-9]+$/", $telepon)) {
            echo "<script>alert('Nomor telepon harus berupa angka !');</script>";
        } else {
            $query = "INSERT INTO member(nama_member, email, password, nomor_telepon, tanggal_awal, tanggal_berakhir, id_paket, id_admin) VALUES ('$nama', '$email', '$pwHash', '$telepon', '$tanggalAwal', '$tanggalAkhir', '$durasi', '$adminId')";

            if ($conn->query($query)) {
                header("Location: admin.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
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
                            <a href="admin-tambah.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/plus.svg" alt="tambah-nav">
                                    Tambah Member
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                        <h2>Tambah Member</h2>
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
                    <form method="POST" class="form-tambah container">
                        <div class="form-group container">
                            <label for="nama">Nama (Sesuai KTP)</label>
                            <input type="text" name="nama" id="nama" class="input-tambah" required>
                        </div>
                        <div class="form-group container">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="input-tambah" required>
                        </div>
                        <div class="form-group container">
                            <label for="nomor-telepon">Nomor Telepon</label>
                            <input type="text" name="nomor-telepon" id="nomor-telepon" class="input-tambah" required>
                        </div>
                        <div class="form-group container">
                            <label for="durasi">Pilih Paket</label>
                            <select name="durasi" id="durasi" class="input-tambah" onchange="updateEndDate()">
                                <option value="1">Regullar - 1 Bulan 8x Fitness</option>
                                <option value="2">Regullar - 1 Bulan Fitness Sepuasnya</option>
                                <option value="3">Regullar - 3 Bulan Fitness Sepuasnya</option>
                                <option value="4">Regullar - 1 Bulan Sepuasnya + 4x Private Fitness</option>
                            </select>
                        </div>
                        <div class="form-group container">
                            <label for="tanggal-awal">Tanggal Awal</label>
                            <input type="date" name="tanggal-awal" id="tanggal-awal" class="input-tambah" value="<?= date('Y-m-d') ?>" onchange="updateEndDate()">
                        </div>
                        <div class="form-group container">
                            <label for="tanggal-akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal-akhir" id="tanggal-akhir" class="input-tambah" onchange="updateEndDate()" required>
                        </div>
                        <div class="btn-group container">
                            <button type="submit" name="submit" class="btn-tambah">Tambah Member</button>
                            <a href="admin.php" class="btn-cancell">Batalkan</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
    <!-- link javascript -->
    <script>
        function updateEndDate() {
            const paketSelect = document.getElementById('durasi');
            const startDateInput = document.getElementById('tanggal-awal');
            const endDateInput = document.getElementById('tanggal-akhir');

            console.log(paketSelect.value);

            const startDate = new Date(startDateInput.value);
            let duration = 0;

            switch (paketSelect.value) {
                case '1':
                    duration = 1; // 1 bulan
                    break;
                case '2':
                    duration = 1; // 1 bulan
                    break;
                case '3':
                    duration = 3; // 3 bulan
                    break;
                case '4':
                    duration = 1; // 1 bulan
                    break;
                default:
                    duration = 0; // default atau paket lainnya
            }

            if (duration > 0) {
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + duration);
                
                // Format tanggal menjadi yyyy-mm-dd
                const month = String(endDate.getMonth() + 1).padStart(2, '0'); 
                const day = String(endDate.getDate()).padStart(2, '0');
                const year = endDate.getFullYear();

                endDateInput.value = `${year}-${month}-${day}`;
            }
        }

    </script>
</body>
</html>