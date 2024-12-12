<?php
    session_start();
    include '../koneksi.php';

    if (!isset($_SESSION['email'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    // mengambil data paket
    $queryTampilPaket = "SELECT id_paket, nama_paket, keterangan_fasilitas, keterangan_durasi, 'Rp ' || TO_CHAR(harga, 'FM999,999,999') as harga, change_null(keterangan_private) as keterangan_private FROM paket_member";
    $resultTampilPaket = $conn->query($queryTampilPaket);
    $rowTampilPaket = $resultTampilPaket->fetchAll(PDO::FETCH_ASSOC);

    // Kirim data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($rowTampilPaket);
?>