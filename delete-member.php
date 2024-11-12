<?php
    // Fungsi untuk memanggil koneksi database 
    include "koneksi.php";

    // variabel untuk memanggil id ketika sudah di klik di halaman sebelumnya
    $id = $_GET['id'];

    // query untuk menghapus data absen
    $queryDeleteAbsen = "DELETE FROM absen_harian WHERE id_member = $id";
    $resultAbsen = $conn->query($queryDeleteAbsen);

    // query untuk menghapus data member
    $queryDeleteMember = "DELETE FROM member WHERE id_member = $id";
    $resultMember = $conn->query($queryDeleteMember);

    // Redirect ke halaman admin setelah penghapusan berhasil
    header("Location: admin.php");
    exit();
?>