<?php 
    namespace Midtrans;
    require_once dirname(__FILE__) . '/midtrans/Midtrans.php';

    // can find in Merchant Portal -> Settings -> Access keys
    Config::$serverKey = 'SB-Mid-server-GSV-k2VgZTn9cvfmTdckGfpz';
    
    session_start();
    include 'koneksi.php';
    require 'send-email.php';

    function rupiah($angka){
        return "Rp " . number_format($angka,0,',','.');
    }

    $order_id = $_GET['order_id'];

    try {
        $transaction = Transaction::status($order_id);

        if ($transaction->transaction_status === 'capture' || $transaction->transaction_status === 'settlement') {
            if(isset($_SESSION['form_data'])) {
                $nama = $_SESSION['form_data']['nama'];
                $email = $_SESSION['form_data']['email'];
                $nomor_telepon = $_SESSION['form_data']['nomor_telepon'];
                $durasi = $_SESSION['form_data']['durasi'];
            }

            // generate password 
            $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $password = '';
            for ($i = 0; $i < 8; $i++) {
                $password .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Hash password sebelum disimpan ke database
            $pwHash = base64_encode($password); 

            $pwDecode = base64_decode($pwHash);

            // Menentukan harga berdasarkan durasi
            $harga_paket = 0;
            $keterangan = '';
            $keteranganDurasi = '';
            $keteranganPrivate = '';

            if($durasi == '1') {
                $harga_paket = 100000;
                $keterangan = '8x Pertemuan';
                $keteranganDurasi = '1 Bulan';
                $keteranganPrivate = '-';
            } else if ($durasi == '2') {
                $harga_paket = 185000;
                $keterangan = 'Bebas Datang';
                $keteranganDurasi = '1 Bulan';
                $keteranganPrivate = '-';
            } else if ($durasi == '3') {
                $harga_paket = 500000;
                $keterangan = 'Bebas Datang';
                $keteranganDurasi = '3 Bulan';
                $keteranganPrivate = '-';
            } else {
                $harga_paket = 550000;
                $keterangan = 'Bebas Datang';
                $keteranganDurasi = '1 Bulan';
                $keteranganPrivate = '4x Pertemuan';
            }

            $biaya_pendaftaran = 50000;
            $total = $harga_paket + $biaya_pendaftaran;

            $subject = 'Konfirmasi Pendaftaran';
            $body = '<p>Hi, ' .$nama. '. Terima kasih sudah bergabung menjadi anggota Tirta Bugar Fitness. Berikut rincian pembayarannya : <p>
                <br>
                <div>
                    <h2>Invoice : ' . $order_id . '</h2>
                </div>
                <div>
                    <p><b>Nama</b> : ' . $nama . '</p>
                </div>
                <div>
                    <p><b>Nomor Telepon</b> : ' . $nomor_telepon . '</p>
                </div>
                <div>
                    <p><b>Email</b> : ' . $email . '</p>
                </div>
                <div>
                    <p><b>Password</b> : ' . $pwDecode  . '</p>
                </div>
                <div>
                    <p><b>Jenis Paket</b> : Fitness Regular </p>
                </div>
                <div>
                    <p><b>Durasi</b> : ' . $keteranganDurasi  . '</p>
                </div>
                <div>
                    <p><b>Keterangan</b> : ' . $keterangan  . '</p>
                </div>
                <div>
                    <p><b>Private Fitness</b> : ' . $keteranganPrivate . '</p>
                </div>
                <div>
                    <p><b>Total yang sudah dibayarkan</b> : ' . rupiah($total)  . '</p>
                </div>';

            // Kirim email
            $result = sendEmail($email, $nama, $subject, $body);

            if($result) {
            if($durasi == '1' || $durasi == '2' || $durasi == '4') {
                    // query menambahkan data member
                    $queryTambahMember = "INSERT INTO member(nama_member, email, password, nomor_telepon, no_kwitansi, status, tanggal_awal, tanggal_berakhir, id_paket) VALUES ('$nama', '$email', '$pwHash', '$nomor_telepon', null, 'aktif', CURRENT_DATE, CURRENT_DATE + INTERVAL '1 month', '$durasi')";
                    $resultMember = $conn->query($queryTambahMember);
                } else {
                    // query menambahkan data member
                    $queryTambahMember = "INSERT INTO member(nama_member, email, password, nomor_telepon, no_kwitansi, status, tanggal_awal, tanggal_berakhir, id_paket) VALUES ('$nama', '$email', '$pwHash', '$nomor_telepon', null, 'aktif', CURRENT_DATE, CURRENT_DATE + INTERVAL '3 month', '$durasi')";
                    $resultMember = $conn->query($queryTambahMember);
                }

                // query menambahkan data transaksi
                $queryTransaksi = "INSERT INTO transaksi(id_paket, invoice, total_harga, tanggal_transaksi,  status_pembayaran, id_member) VALUES ('$durasi', '$order_id', $total , CURRENT_DATE, 'successful', (SELECT id_member FROM member WHERE nomor_telepon = '$nomor_telepon'))";
                $resultTransaksi = $conn->query($queryTransaksi);

                if($resultMember && $resultTransaksi) {
                    // Hapus data session setelah berhasil
                    unset($_SESSION['form_data']);
                }
            }
        } else {
            echo "<script>alert('Pembayaran gagal diproses!'); window.location.href = 'index.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Pembayaran gagal diproses!'); window.location.href = 'index.php';</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Tirta Bugar Fitness</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pembayaran-berhasil.css">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <div class="sukses container">
        <h1>Pembayaran berhasil, silahkan cek email anda</h1>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 5000);
    </script>
</body>
</html>