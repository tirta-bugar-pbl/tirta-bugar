<?php 
    include '../koneksi.php';

    // logika pagination
    $limit = 10; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $queryTransaksi = "SELECT * FROM view_member_transaction_list WHERE 1=1";

    // kondisi searching 
    if (!empty($search)) {
        $queryTransaksi .= " AND LOWER(nama_member) LIKE '%' || LOWER('$search') || '%'";
    } 

    // Tambahkan filter waktu
    if ($filter == 'today') {
        $queryTransaksi .= " AND DATE(tanggal_transaksi) = CURRENT_DATE";
    } else if ($filter == 'week') {
        $queryTransaksi .= " AND DATE(tanggal_transaksi) >= (CURRENT_DATE - INTERVAL '7 days')";
    } else if ($filter == 'month') {
        $queryTransaksi .= " AND DATE_PART('month', tanggal_transaksi) = DATE_PART('month', CURRENT_DATE) AND DATE_PART('year', tanggal_transaksi) = DATE_PART('year', CURRENT_DATE)";
    }

    // Hitung total transaksi hanya jika tidak ada pencarian
    $totalCount = 0;
    if (empty($search)) {
        $queryCount = "SELECT COUNT(DISTINCT id_transaksi) AS total FROM view_member_transaction_list WHERE 1=1";
        if ($filter == 'today') {
            $queryCount .= " AND DATE(tanggal_transaksi) = CURRENT_DATE";
        } else if ($filter == 'week') {
            $queryCount .= " AND DATE(tanggal_transaksi) >= (CURRENT_DATE - INTERVAL '7 days')";
        } else if ($filter == 'month') {
            $queryCount .= " AND DATE_PART('month', tanggal_transaksi) = DATE_PART('month', CURRENT_DATE) AND DATE_PART('year', tanggal_transaksi) = DATE_PART('year', CURRENT_DATE)";
        }
        $resultCount = $conn->query($queryCount);
        $totalCount = $resultCount->fetch(PDO::FETCH_ASSOC)['total'];
    }

    $totalPages = $totalCount > 0 ? ceil($totalCount / $limit) : 1;

    // Tambahkan LIMIT dan OFFSET untuk pagination
    $queryTransaksi .= " LIMIT $limit OFFSET $offset";

    // Eksekusi query
    $resultQuery = $conn->query($queryTransaksi);
    $resultTransaksi = $resultQuery->fetchAll(PDO::FETCH_ASSOC);

    // Kirim data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'transactions' => $resultTransaksi,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
?>