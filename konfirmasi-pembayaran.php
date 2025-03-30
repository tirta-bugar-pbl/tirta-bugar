<?php 
    include 'koneksi.php';
    session_start();

    // Mengambil data dari session
    if (isset($_SESSION['form_data'])) {
        $nama = $_SESSION['form_data']['nama'];
        $email = $_SESSION['form_data']['email'];
        $nomor_telepon = $_SESSION['form_data']['nomor_telepon'];
        $durasi = $_SESSION['form_data']['durasi'];
    } else {
        echo "<script>alert('Data pendaftaran tidak ditemukan.'); window.location.href='daftar.php';</script>";
        exit;
    }

    // Generate password 
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $password = '';
    for ($i = 0; $i < 8; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    $pwHash = base64_encode($password); 

    // Generate nomor invoice sederhana
    $invoice = rand();

    // Ambil data paket dari database
    $queryPaket = "SELECT nama_paket, harga, keterangan_fasilitas, keterangan_durasi, change_null(keterangan_private) FROM paket_member WHERE id_paket = '$durasi'";
    $queryResult = $conn->prepare($queryPaket);
    $rowPacket = $result->fetch(PDO::FETCH_ASSOC);

    if($rowPacket) {
        $nama_paket = $rowPacket['nama_paket'];
        $harga_paket = $rowPacket['harga'];
        $keterangan = $rowPacket['keterangan_fasilitas'];
        $keteranganDurasi = $rowPacket['keterangan_durasi'];
        $keteranganPrivate = $rowPacket['keterangan_private'];
    }

    $biaya_pendaftaran = 50000;
    $total = $harga_paket + $biaya_pendaftaran;

    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    // Proses ketika tombol Bayar diklik
    if (isset($_POST['submit'])) {
        try {
            // Query untuk mendapatkan data paket
            $queryPaket = "SELECT harga, keterangan_fasilitas, keterangan_durasi, keterangan_private FROM paket_member WHERE id_paket = '$durasi'";
            $resultPaket = $conn->query($queryPaket);
            $paket = $resultPaket->fetch(PDO::FETCH_ASSOC);

            if($paket) {
                $queryTransaksi = "CALL tambah_member_transaksi('$nama','$email','$pwHash', '$nomor_telepon','$durasi','$invoice','$harga_paket')";

                $resultTransaksi = $conn->query($queryTransaksi);  

                /*
                $queryTambahMember = "INSERT INTO member(nama_member, email, password, nomor_telepon, tanggal_awal, tanggal_berakhir, id_paket) VALUES ('$nama', '$email', '$pwHash', '$nomor_telepon', null, 'aktif', CURRENT_DATE, CURRENT_DATE + INTERVAL '$durasi month', '$durasi')";

                $resultMember = $conn->query($queryTambahMember);   

                $queryTransaksi = "INSERT INTO transaksi(id_paket, invoice, total_harga, tanggal_transaksi, id_member) VALUES ('$durasi', '$invoice', hitung_biaya_daftar('$harga_paket') , CURRENT_DATE, (SELECT id_member FROM member WHERE nomor_telepon = '$nomor_telepon'))";

                $resultTransaksi = $conn->query($queryTransaksi);

                
                if ($resultMember && $resultTransaksi) {
                    unset($_SESSION['form_data']);
                    unset($_SESSION['invoice']);
                    echo "<script>alert('Pembayaran berhasil diproses!'); window.location.href = 'index.php';</script>";
                }
                */

                if ($resultTransaksi) {
                    unset($_SESSION['form_data']);
                    unset($_SESSION['invoice']);
                    echo "<script>alert('Pembayaran berhasil diproses!'); window.location.href = 'pembarayan-berhasil.php';</script>";
                }
            }
        } catch (Exception $e) {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Tirta Bugar Fitness</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pembayaran.css">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
</head>
<body>
    <?php if(isset($_SESSION['form_data'])): ?>
    <h2 class="title-payment">Payment</h2>
    <div class="payment-box">
        <h3 class="title-detail-payment">Detail Pesanan</h3>
        <h5 class="title-invoice">#<?php echo $invoice; ?></h5>
        <div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Nama</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($nama) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Nomor Telepon</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($nomor_telepon) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Jenis Paket</p>
                    <p>:</p>
                </div>
                <p>Fitness <?= htmlspecialchars($nama_paket) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Durasi</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($keteranganDurasi) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Keterangan</p>
                    <p>:</p>
                </div>
                <p><?= htmlspecialchars($keterangan) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Harga Paket</p>
                    <p>:</p>
                </div>
                <p><?= rupiah($harga_paket) ?></p>
            </div>
            <div class="detail-payment-group container">
                <div class="label-detail-payment container">
                    <p>Biaya Pendaftaran</p>
                    <p>:</p>
                </div>
                <p><?= rupiah($biaya_pendaftaran) ?></p>
            </div>
        </div>
        <div class="detail-payment-group total container">
            <h4 class="label-total-amount">Total Biaya</h4>
            <h5 class="total-amount"><?= rupiah($total) ?></h5>
        </div>
    </div>
    <form method="POST">
        <button type="submit" name="submit" class="btn-payment">Bayar</button>
    </form>
    <?php else: ?>
    <div class="alert alert-danger">
        <p>Tidak ada data pendaftaran yang ditemukan. Silakan kembali ke halaman <a href="daftar.php">pendaftaran</a>.</p>
    </div>
    <?php endif; ?>
</body>
</html>
