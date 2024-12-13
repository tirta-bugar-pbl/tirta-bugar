<?php
    include 'koneksi.php';

    // Query untuk mendapatkan member yang akan segera atau sudah expire
    $queryNonActiveMembers = "SELECT nama_member, format_tanggal_berakhir FROM view_member_list WHERE selisih = 0 ORDER BY tanggal_berakhir ASC LIMIT 5";

    $resultNonActiveMembers = $conn->query($queryNonActiveMembers);
    $nonActiveMembers = $resultNonActiveMembers->fetchAll(PDO::FETCH_ASSOC);

    // Generate JSON
    $notificationsData = [
        'hasNotifications' => count($nonActiveMembers) > 0,
        'count' => count($nonActiveMembers),
        'messages' => array_map(function($member) {
            return "{$member['nama_member']} masa berlakunya sudah habis pada " . $member['format_tanggal_berakhir'];
        }, $nonActiveMembers)
    ];

    header('Content-Type: application/json');
    echo json_encode($notificationsData);
    exit();

?>