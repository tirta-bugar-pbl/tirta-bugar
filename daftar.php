<?php 
        include 'koneksi.php';
        session_start();

        $id_paket = isset($_GET['id_paket']) ? $_GET['id_paket'] : '';

        if (isset($_POST['submit'])) {
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $telepon = $_POST['nomor-telepon'];
            $durasi = $_POST['durasi'];
            $order_id = rand();

            // Validasi nomor telepon harus angka
            if (!preg_match("/^[0-9]+$/", $telepon)) {
                echo "<script>alert('Nomor telepon harus berupa angka!');</script>";
            } else {

                // query untuk mendapatkan data member
                $queryMember = "SELECT email, nomor_telepon FROM member WHERE email = '$email'  nomor_telepon = '$telepon'";
                $resultMember = $conn->query($queryMember);
                $rowMember = $resultMember->fetch(PDO::FETCH_ASSOC);

                if($rowMember) {
                    echo "<script>
                        alert('Email atau nomor telepon sudah terdaftar !');
                    </script>";
                } else {
                    // Simpan data ke session jika valid
                    $_SESSION['form_data'] = [
                        'nama' => $nama,
                        'email' => $email,
                        'nomor_telepon' => $telepon,
                        'durasi' => $durasi
                    ];

                    // Redirect ke konfirmasi-pembayaran.php
                    // header("Location: konfirmasi-pembayaran.php");
                    header("Location: ./midtrans/examples/snap/checkout-process-simple-version.php?order_id=$order_id");
                    exit;
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - Tirta Bugar Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/daftar-style.css">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <h1 class="title-daftar">Formulir Pendaftaran</h1>
        <form class="daftar container" method="POST" action="daftar.php">
            <div class="form-group container">
                <label for="nama">Nama (Sesuai KTP)</label>
                <input type="text" name="nama" id="nama" class="input-daftar" required>
            </div>
            <div class="form-group container">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="input-daftar" required>
            </div>
            <div class="form-group container">
                <label for="nomor-telepon">Nomor Telepon</label>
                <input type="text" name="nomor-telepon" id="nomor-telepon" class="input-daftar" required>
            </div>
            <div class="form-group container">
                <label for="durasi">Pilihan Paket</label>
                <select name="durasi" id="durasi" class="input-daftar">
                    <option value="1" <?php if($id_paket == 1) echo "selected" ?>>Regullar - 1 Bulan 8x Fitness</option>
                    <option value="2" <?php if($id_paket == 2) echo "selected" ?>>Regullar - 1 Bulan Fitness Sepuasnya</option>
                    <option value="3" <?php if($id_paket == 3) echo "selected" ?>>Regullar - 3 Bulan Fitness Sepuasnya</option>
                    <option value="4" <?php if($id_paket == 4) echo "selected" ?>>Regullar - 1 Bulan Sepuasnya + 4x Private Fitness</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn-daftar">Daftar</button>
        </form>
    </div>
</body>
</html>
