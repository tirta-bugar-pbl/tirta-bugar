<?php
    session_start();
    include '../koneksi.php';

    if (!isset($_SESSION['email'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    // mengambil data detail member
    $id = $_GET['id'];
    // $queryDetailMember = "SELECT m.nama_member, m.email, m.password, m.nomor_telepon, TO_CHAR(m.tanggal_awal, 'DD Month YYYY') as tanggal_awal, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, p.nama_paket, p.keterangan_fasilitas, p.keterangan_durasi, COALESCE(p.keterangan_private, '-') as keterangan_private FROM member m LEFT OUTER JOIN paket_member p ON m.id_paket = p.id_paket WHERE m.id_member = $id";
    $queryDetailMember = "SELECT * FROM view_detail_member WHERE id_member = $id";
    $resultDetailMember = $conn->query($queryDetailMember);
    $rowDetailMember = $resultDetailMember->fetch(PDO::FETCH_ASSOC);

    // Kirim data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($rowDetailMember);
?>