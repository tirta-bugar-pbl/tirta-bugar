<?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['email'])) {
        header('Location: admin-login.php');
        exit();
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
    <!-- link favicon -->
    <link rel="shortcut icon" href="assets/logo-favicon.png" type="image/x-icon">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src= notifications.js></script>
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
                                <h4 id="total-member">0</h4>
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
                                <h4 id="total-member-active">0</h4>
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
                                <h4 id="total-member-nonactive">0</h4>
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
                                            <option value="all-asc">A - Z</option>
                                            <option value="all-desc">Z - A</option>
                                            <option value="aktif">Members Aktif</option>
                                            <option value="tidak_aktif">Member Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- sort member by date -->
                                <div>
                                    <div class="filter-member">
                                        <select name="sort_by_date" id="sort_by_date">
                                            <option value="">Sort by Date</option>
                                            <option value="asc">Lama ke Baru</option>
                                            <option value="desc">Baru ke Lama</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- search member -->
                            <div class="search-member container">
                                <input type="text" name="search" id="search" placeholder="Search">
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
                                <td style="text-align: center;width: 16%;">Nama</td>
                                <td style="text-align: center; width: 15%;">Nomor Telepon</td>
                                <td style="text-align: center; width: 10%;">Durasi</td>
                                <td style="text-align: center; width: 15%;">Keterangan</td>
                                <td style="text-align: center; width: 15%;">Private Fitness</td>
                                <td style="text-align: center; width: 15%;">Tanggal Berlaku</td>
                                <td style="text-align: center; width: 15%;">Aksi</td>
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
        document.getElementById('combined_filter').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('sort_by_date').addEventListener('change', function() {
            this.form.submit();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const combinedFilter = document.getElementById('combined_filter');
            const sortByDate = document.getElementById('sort_by_date');
            const searchInput = document.getElementById('search');
            const searchForm = document.querySelector('form');

            // Fungsi untuk mengatur pilihan yang sudah dipilih sebelumnya
            function setInitialSelections() {
                const urlParams = new URLSearchParams(window.location.search);
                const combinedFilterValue = urlParams.get('combined_filter');
                const sortByDateValue = urlParams.get('sort_by_date');

                if (combinedFilterValue) {
                    combinedFilter.value = combinedFilterValue;
                }
                if (sortByDateValue) {
                    sortByDate.value = sortByDateValue;
                }
            }

            // Fungsi untuk memuat data member
            function fetchData(page = 1) {
                const params = new URLSearchParams({
                    page: page,
                    combined_filter: combinedFilter.value,
                    search: searchInput.value,
                    sort_by_date: sortByDate.value
                });

                fetch('./json/tampil-data-member.php?' + params.toString())
                    .then(response => response.json())
                    .then(data => {
                        renderTable(data.members);
                        renderPagination(data.totalPages, page);
                        renderAmount(data.total_member, data.total_member_aktif, data.total_member_nonaktif);
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Fungsi untuk menampilkan data member
            function renderTable(members) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';

                members.forEach(member => {
                    const tr = document.createElement('tr');
                    if(member.selisih == 0) {
                        tr.classList.add('habis');
                    }
                    tr.innerHTML = `
                        <td>${member.nama_member}</td>
                        <td style="text-align: center;">${member.nomor_telepon}</td>
                        <td style="text-align: center;">${member.keterangan_durasi}</td>
                        <td style="text-align: center;">${member.keterangan_fasilitas}</td>
                        <td style="text-align: center;">${member.keterangan}</td>
                        <td style="text-align: center;">${member.tanggal_berakhir_format}</td>
                        <td>
                            <div class="action container">
                                <a href="admin-detail.php?id=${member.id_member}" class="detail">Detail</a>
                                <a href="delete-member.php?id=${member.id_member}" class="hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus member ini?')">Hapus</a>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // fungsi render pagination
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

            // fungsi render jumlah
            function renderAmount(totalMember, totalMemberAktif, totalMemberNonaktif) {
                const totalMemberElement = document.getElementById('total-member');
                const totalMemberAktifElement = document.getElementById('total-member-active');
                const totalMemberNonaktifElement = document.getElementById('total-member-nonactive');

                totalMemberElement.textContent = totalMember;
                totalMemberAktifElement.textContent = totalMemberAktif;
                totalMemberNonaktifElement.textContent = totalMemberNonaktif;
            }

            combinedFilter.addEventListener('change', () => fetchData(1));
            sortByDate.addEventListener('change', () => fetchData(1));

            searchForm.addEventListener('submit', function(event) {
                event.preventDefault();
                fetchData(1);
            });

            setInitialSelections();
            fetchData();
        });

</script>
</body>
</html>