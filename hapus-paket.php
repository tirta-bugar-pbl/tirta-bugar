<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    header('Location: admin-login.php');
    exit();
}

// Pastikan ada data yang dikirim
if (isset($_POST['id_paket'])) {
    $idPaket = $_POST['id_paket'];

    // Query untuk menghapus data
    $queryHapus = "DELETE FROM paket_member WHERE id_paket = :id_paket";
    $stmt = $conn->prepare($queryHapus);

    // Jalankan query
    if ($stmt->execute([':id_paket' => $idPaket])) {
        echo "<script>alert('Paket berhasil dihapus!'); window.location.href='admin-paket.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus paket!'); window.location.href='admin-paket.php';</script>";
    }
} else {
    header('Location: admin-paket.php');
    exit();
}
?>