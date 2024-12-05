<?php
include 'koneksi.php';

// Query untuk mendapatkan member yang akan segera atau sudah expire
$queryNonActiveMembers = "
    SELECT nama_member, 
           nomor_telepon, 
           tanggal_berakhir 
    FROM member 
    WHERE tanggal_berakhir <= CURRENT_DATE + INTERVAL '7 days'
    ORDER BY tanggal_berakhir ASC
";
$resultNonActiveMembers = $conn->query($queryNonActiveMembers);
$nonActiveMembers = $resultNonActiveMembers->fetchAll(PDO::FETCH_ASSOC);

// Generate JSON
$notificationsData = [
    'hasNotifications' => count($nonActiveMembers) > 0,
    'count' => count($nonActiveMembers),
    'messages' => array_map(function($member) {
        return "Membership {$member['nama_member']} akan berakhir pada " . 
               date('d F Y', strtotime($member['tanggal_berakhir']));
    }, $nonActiveMembers)
];

header('Content-Type: application/json');
echo json_encode($notificationsData);
exit();