<?php
    session_start();
    include '../koneksi.php';

    if (!isset($_SESSION['email'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    // variabel pagination
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Mengambil search
    $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

    // Query untuk data absen
    $queryAbsen = "SELECT * FROM view_member_absen_list";

    if ($search) {
        $queryAbsen .= " WHERE LOWER(nama_member) LIKE '%" . $search . "%'";
    }
    $queryAbsen .= " LIMIT $limit OFFSET $offset";

    $resultQuery = $conn->query($queryAbsen);
    $resultAbsen = $resultQuery->fetchAll(PDO::FETCH_ASSOC);

    // Hitung total absen untuk pagination
    $queryCount = "SELECT COUNT(DISTINCT id_pertemuan) AS total FROM view_member_absen_list";
    if ($search) {
        $queryCount .= " WHERE LOWER(nama_member) LIKE '%" . $search . "%'";
    }
    $totalCount = $conn->query($queryCount)->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalCount / $limit);

    // Kirim data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'absens' => $resultAbsen,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);

?>