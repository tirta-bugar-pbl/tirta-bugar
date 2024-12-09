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

    // Mengambil nilai filter dari URL
    $combinedFilter = isset($_GET['combined_filter']) ? $_GET['combined_filter'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sortByDate = isset($_GET['sort_by_date']) ? $_GET['sort_by_date'] : '';

    // Query dasar
    $baseQuery = "SELECT * FROM view_member_list WHERE 1=1";

    // Tambahkan kondisi pencarian
    if (!empty($search)) {
        $baseQuery .= " AND LOWER(nama_member) LIKE '%' || LOWER('$search') || '%'";
    }

    // Array untuk ORDER BY clauses
    $orderClauses = array();

    // Tambahkan kondisi filter gabungan
    if ($combinedFilter) {
        list($status) = explode('-', $combinedFilter);

        if ($status !== 'all') {
            if ($status === 'aktif') {
                $baseQuery .= " AND tanggal_berakhir > CURRENT_DATE";
            } else if ($status === 'tidak_aktif') {
                $baseQuery .= " AND tanggal_berakhir <= CURRENT_DATE";
            }
        }

        // Menggunakan `$combinedFilter` untuk menentukan sort
        if ($combinedFilter === 'all-asc') {
            $orderClauses[] = "nama_member ASC";
        } else if ($combinedFilter === 'all-desc') {
            $orderClauses[] = "nama_member DESC";
        }
    }

    // Tambahkan sort by date
    if ($sortByDate) {
        $orderClauses[] = "tanggal_berakhir " . ($sortByDate === 'asc' ? 'ASC' : 'DESC') . " NULLS LAST";
    }

    // Tambahkan ORDER BY ke query jika ada
    if (!empty($orderClauses)) {
        $baseQuery .= " ORDER BY " . implode(", ", $orderClauses);
    }

    // Query untuk menghitung total records
    $countQuery = preg_replace('/SELECT.*?FROM/s', 'SELECT COUNT(*) as total FROM', $baseQuery);
    $countQuery = preg_replace('/ORDER BY.*$/', '', $countQuery);

    // Eksekusi query untuk menghitung total records
    $stmt = $conn->query($countQuery);
    $rowCount = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPages = ceil($rowCount['total'] / $limit);

    // Tambahkan LIMIT dan OFFSET
    $baseQuery .= " LIMIT $limit OFFSET $offset";

    // Eksekusi query final
    $stmt = $conn->query($baseQuery);
    $resultMember = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Statistik tetap sama seperti sebelumnya
    $queryAmountMember = "SELECT COUNT(id_member) As total_member FROM member";
    $resultAmountMember = $conn->query($queryAmountMember);
    $rowAmountMember = $resultAmountMember->fetch(PDO::FETCH_ASSOC);

    // Menghitung jumlah member aktif
    $queryAmountMemberActive = "SELECT COUNT(id_member) AS total_member_aktif FROM view_member_list WHERE tanggal_berakhir > CURRENT_DATE";
    $resultAmountMemberActive = $conn->query($queryAmountMemberActive);
    $rowAmountMemberActive = $resultAmountMemberActive->fetch(PDO::FETCH_ASSOC);

    // Menghitung jumlah member tidak aktif
    $queryAmountMemberNonactive = "SELECT COUNT(id_member) AS total_member_nonaktif FROM view_member_list WHERE tanggal_berakhir <= CURRENT_DATE";
    $resultAmountMemberNonactive = $conn->query($queryAmountMemberNonactive);
    $rowAmountMemberNonactive = $resultAmountMemberNonactive->fetch(PDO::FETCH_ASSOC);

    // Kirim data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'members' => $resultMember,
        'totalPages' => $totalPages,
        'pages' => $page,
        'total_member' => $rowAmountMember['total_member'],
        'total_member_aktif' => $rowAmountMemberActive['total_member_aktif'],
        'total_member_nonaktif' => $rowAmountMemberNonactive['total_member_nonaktif'],
    ]);
?>