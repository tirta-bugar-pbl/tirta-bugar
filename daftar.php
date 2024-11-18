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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - Tirta Bugar Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/daftar-style.css">
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
                <label for="durasi">Durasi</label>
                <select name="durasi" id="durasi" class="input-daftar">
                    <option value="1" <?php if($id_paket == 1) echo "selected" ?>>8x Pertemuan</option>
                    <option value="2" <?php if($id_paket == 2) echo "selected" ?>>1 Bulan</option>
                    <option value="3" <?php if($id_paket == 3) echo "selected" ?>>3 Bulan</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn-daftar">Daftar</button>
        </form>
    </div>
</body>
</html>
