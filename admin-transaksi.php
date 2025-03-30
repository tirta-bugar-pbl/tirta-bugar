<?php 
    session_start();
    include 'koneksi.php';

    if(!isset($_SESSION['email'])){
        header('Location: admin-login.php');
        exit();
    }

    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    // mengambil data profile di php
    $adminId = $_SESSION['id_admin'];
    $queryProfileName = "SELECT username FROM admin WHERE id_admin = $adminId";
    $resultProfileName = $conn->query($queryProfileName);
    $rowProfileName = $resultProfileName->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-transaksi.css?v=<?php echo time(); ?>">
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="notifications.js"></script>

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
                            <a href="admin.php" class="menu-item">
                                <div class="menu container">
                                    <img src="assets/home.svg" alt="dashboard-nav">
                                    Beranda
                                </div>
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
                            <a href="admin-transaksi.php" class="menu-item container">
                                <div class="menu container">
                                    <img src="assets/transaction.svg" alt="transaction-nav">
                                    Transaksi
                                </div>
                                <img src="assets/active-menu.svg" alt="active-icon">
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
                        <h2>Transaksi</h2>
                    </div>
                    <div class="account">
            <!-- notif account -->
            <div id="notification-container" class="notification-container">
                <div class="notification-icon-wrapper">
                    <img src="assets/notification.svg" alt="notification" id="notificationIcon">
                    <span class="notification-badge hidden"></span>
                </div>
            </div>
            <div class="account-profile">
                <!-- icon account -->
                <img src="assets/profile.svg" alt="profile">
                <h3><?= $rowProfileName['username']?></h3>
            </div>
        </div>
            </div>
                </header>
        
            <!-- Pop-Up Notification -->
        <div id="notification-popup" class="popup hidden">
            <div class="popup-content">
                <span id="close-popup" class="close">&times;</span>
                <ul id="notification-list"></ul>
            </div>
        </div>
            <main>
                <!-- filtering transaksi -->
                <section class="filtering-transaksi">
                    <form method="GET" class="container">
                        <!-- filter member -->
                        <div class="filter-transaksi">
                            <select name="filter" id=filter onchange="applyFilter()">
                                <option value="">Filter</option>
                                <option value="today">Hari Ini</option>
                                <option value="week">Minggu Ini</option>
                                <option value="month">Bulan ini</option>
                            </select>
                        </div>
                        <!-- search member -->
                        <div class="search-transaksi container">
                            <input type="text" name="search" id="search" placeholder="Search">
                            <img src="assets/search.svg" alt="search">
                        </div>
                </section>
                <section class="member-table">
                    <table>
                        <!-- head table -->
                        <thead>
                            <tr>
                                <td style="text-align: center;width: 15%;">Tanggal Transaksi</td>
                                <td style="text-align: center;width: 10%;">Invoice</td>
                                <td style="text-align: center;width: 15%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 10%;">Status Pembayaran</td>
                                <td style="text-align: center; width: 15%;">Total Bayar</td>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </section>
                <!-- Pagination -->
                <section class="pagination">
                    <div class="container">
                        <ul>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filter');
            const searchInput = document.getElementById('search');
            const searchForm = document.querySelector('form');

            // konversi ke mata uang rupiah
            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // pengambilan data
            function fetchData(page = 1) {
                const params = new URLSearchParams({
                    page: page,
                    filter: filterSelect.value,
                    search: searchInput.value,
                });

                fetch('./json/tampil-data-transkasi.php?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        renderTable(data.transactions);
                        renderPagination(data.totalPages, data.currentPage);
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // render table
            function renderTable(transactions) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';

                transactions.forEach(transaction => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="text-align: center;">${transaction.tanggal_transaksi_formated}</td>
                        <td style="text-align: center;">${transaction.invoice}</td>
                        <td>${transaction.nama_member}</td>
                        <td style="text-align: center;">${transaction.nomor_telepon}</td>
                        <td style="text-align: center;">${transaction.keterangan_durasi}</td>
                        <td style="text-align: center;">${transaction.keterangan_fasilitas}</td>
                        <td style="text-align: center;"><b>${transaction.status_pembayaran}</b></td>
                        <td style="text-align: center;">${transaction.total_harga}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // render pagination
            function renderPagination(totalPages, currentPage) {
                const pagination = document.querySelector('.pagination ul');
                pagination.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.innerHTML = `<a href="#" class="${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
                    pagination.appendChild(li);
                }

                document.querySelectorAll('.pagination a').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        const page = parseInt(this.getAttribute('data-page'));
                        fetchData(page);
                    });
                });
            }

            filterSelect.addEventListener('change', () => fetchData(1));
            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                fetchData(1);
            });

            fetchData();
        });
    </script>

</body>
</html>