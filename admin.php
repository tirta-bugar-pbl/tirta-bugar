<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['email'])) {
        header('Location: admin-login.php');
        exit();
    }

    // variabel pagination
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    // mengambil data profile
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT username FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);

    // Mengambil nilai filter dari URL
    $combinedFilter = isset($_GET['combined_filter']) ? $_GET['combined_filter'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sortByDate = isset($_GET['sort_by_date']) ? $_GET['sort_by_date'] : '';

    // Query dasar
    $baseQuery = "SELECT m.id_member, m.nama_member, m.nomor_telepon, p.keterangan_durasi, p.keterangan_fasilitas, m.tanggal_berakhir, TO_CHAR(m.tanggal_berakhir, 'DD Month YYYY') as tanggal_berakhir, (m.tanggal_berakhir - m.tanggal_awal) AS selisih FROM member m LEFT JOIN paket_member p ON p.id_paket = m.id_paket WHERE 1=1";

    // Tambahkan kondisi pencarian
    if (!empty($search)) {
        $searchTerm = '%' . strtolower($search) . '%';
        $baseQuery .= " AND LOWER(m.nama_member) LIKE '$searchTerm'";
    }

    // Array untuk ORDER BY clauses
    $orderClauses = array();

    // Tambahkan kondisi filter gabungan
    if ($combinedFilter) {
        list($status) = explode('-', $combinedFilter);
        
        if ($status !== 'all') {
            if ($status === 'aktif') {
                $baseQuery .= " AND m.tanggal_berakhir > CURRENT_DATE";
            } else if ($status === 'tidak_aktif') {
                $baseQuery .= " AND m.tanggal_berakhir <= CURRENT_DATE";
            }
        }
        
        // if ($sort === 'asc') {
        //     $orderClauses[] = "m.nama_member ASC";
        // } else if ($sort === 'desc') {
        //     $orderClauses[] = "m.nama_member DESC";
        // }
    }

    // Tambahkan sort by date
    if ($sortByDate) {
        $orderClauses[] = "m.tanggal_berakhir " . ($sortByDate === 'asc' ? 'ASC' : 'DESC') . " NULLS LAST";
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
    $queryAmountMemberActive = "SELECT COUNT(id_member) AS total_member_aktif FROM member WHERE tanggal_berakhir > CURRENT_DATE";
    $resultAmountMemberActive = $conn->query($queryAmountMemberActive);
    $rowAmountMemberActive = $resultAmountMemberActive->fetch(PDO::FETCH_ASSOC);

    // Menghitung jumlah member tidak aktif
    $queryAmountMemberNonactive = "SELECT COUNT(id_member) AS total_member_nonaktif FROM member WHERE tanggal_berakhir <= CURRENT_DATE";
    $resultAmountMemberNonactive = $conn->query($queryAmountMemberNonactive);
    $rowAmountMemberNonactive = $resultAmountMemberNonactive->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- sidebar -->
        <div class="sidebar container">
            <!-- sidebar layout -->
            <div class="navbar-menu">
                <!-- sidebar logo -->
                <div class="logo-sidebar">
                    <h1>TB</h1>
                </div>
                <!-- sidebar menu -->
                <nav>
                    <ul>
                        <li>
                            <a href="admin.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/home.svg" alt="dashboard-nav">
                                    Beranda
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
                            </a>
                        </li>
                        <li>
                            <a href="admin-tambah.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/plus.svg" alt="tambah-nav">
                                    Tambah Member
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-paket.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/note.svg" alt="paket-nav">
                                    Daftar Paket
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-transaksi.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/transaction.svg" alt="transaction-nav">
                                    Transaksi
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-akun.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/setting.svg" alt="setting-nav">
                                    Pengaturan Akun
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="admin-absen.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/calendar.svg" alt="calendar-nav">
                                    Absensi Harian
                                </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- sidebar log out -->
            <a href="logout.php" class="log-out container">
                <img src="assets/log-out.svg" alt="log-out">
                <h3>Log Out</h3>
            </a>
        </div>
        <div class="content">
            <header>
                <div class="container">
                    <div class="title-page">
                        <h2>Beranda</h2>
                    </div>
                    <div class="account">
                        <!-- notif account -->
                        <img src="assets/notification.svg" alt="notifivation">
                        <div class="account-profile">
                            <!-- icon account -->
                            <img src="assets/profile.svg" alt="profile">
                            <h3><?= $rowProfileName['username']?></h3>
                        </div>
                    </div>
                </div>
            </header>
            <main>
                <!-- amount member -->
                <section class="amount-member">
                    <!-- card amount member layout -->
                    <div class="container">
                        <!-- card member -->
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-member.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Jumlah Member</h3>
                                <h4><?= $rowAmountMember['total_member']?></h4>
                            </div>
                        </div>
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-active.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Masih Aktif</h3>
                                <h4><?= $rowAmountMemberActive['total_member_aktif'] ?></h4>
                            </div>
                        </div>
                        <div class="card-member-amount container">
                            <!-- image amount -->
                            <div class="image-amount container">
                                <img src="assets/amount-nonactive.svg" alt="amount-member">
                            </div>
                            <!-- title amount -->
                            <div class="title-amount-group">
                                <h3>Tidak Aktif</h3>
                                <h4><?= $rowAmountMemberNonactive['total_member_nonaktif']?></h4>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- filtering member -->
                <section class="filtering-member">
                    <form method="GET">
                        <div class="filter-group container">
                            <div class="f container">
                                <!-- Combined filter for status and name -->
                                <div>
                                    <div class="filter-member">
                                        <select name="combined_filter" id="combined_filter">
                                            <option value="">Filter & Sort</option>
                                            <option value="all-asc" <?= $combinedFilter === 'all-asc' ? 'selected' : '' ?>>A - Z</option>
                                            <option value="all-desc" <?= $combinedFilter === 'all-desc' ? 'selected' : '' ?>>Z - A</option>
                                            <option value="aktif" <?= $combinedFilter === 'aktif' ? 'selected' : '' ?>>Members Aktif</option>
                                            <option value="tidak_aktif" <?= $combinedFilter === 'tidak_aktif' ? 'selected' : '' ?>>Member Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- sort member by date -->
                                <div>
                                    <div class="filter-member">
                                        <select name="sort_by_date" id="sort_by_date">
                                            <option value="">Sort by Date</option>
                                            <option value="asc" <?= $sortByDate === 'asc' ? 'selected' : '' ?>>Lama ke Baru</option>
                                            <option value="desc" <?= $sortByDate === 'desc' ? 'selected' : '' ?>>Baru ke Lama</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- search member -->
                            <div class="search-member container">
                                <input type="text" name="search" id="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>">
                                <img src="assets/search.svg" alt="search">
                            </div>    
                        </div>
                    </form>
                </section>
    
                <section class="member-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 20%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 15%;">Tanggal Berlaku</td>
                                <td style="text-align: center; width: 15%;">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resultMember as $result) : ?>
                            <?php 
                                $style = '';

                                // kondisi menentukan 
                                if($result['selisih'] == 7) {
                                    $style = "style='background-color: yellow';";
                                } elseif ($result['selisih'] == 0) {
                                    $style = "style='background-color: red';";
                                } 
                            ?>
                            <tr <?= $style ?>>
                                <td><?= $result['nama_member']?></td>
                                <td style="text-align: center;"><?= $result['nomor_telepon']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_durasi']?></td>
                                <td style="text-align: center;"><?= $result['keterangan_fasilitas']?></td>
                                <td style="text-align: center;"><?= $result['tanggal_berakhir']?></td>
                                <td>
                                    <div class="action container">
                                        <a href="admin-detail.php?id=<?=$result['id_member']; ?>" class="detail">Detail</a>
                                        <a href="delete-member.php?id=<?=$result['id_member']; ?>" class="hapus"  onclick="return confirm('Apakah Anda yakin ingin menghapus member ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
                <!-- Pagination -->          
                <section class="pagination">
                    <div class="container">
                        <ul>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li>
                                    <a href="?page=<?= $i ?>&combined_filter=<?= urlencode($combinedFilter) ?>&search=<?= urlencode($search) ?>&sort_by_date=<?= urlencode($sortByDate) ?>"  class="<?= ($i == $page) ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </section>
                </main>
        </div>
    </div>
    <script>
        document.getElementById('combined_filter').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('sort_by_date').addEventListener('change', function() {
            // Reset combined_filter saat memilih sort by date
            document.getElementById('combined_filter').value = '';  // Reset combined_filter
            this.form.submit();  // Submit form setelah mereset combined_filter
        });
    </script>
</body>
</html>