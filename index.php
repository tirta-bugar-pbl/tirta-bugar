<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tirta Bugar Fitness</title>
    <!-- link css -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <!-- link google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <div class="logo-brand">
                <h1>TB</h1>
            </div>
            <nav class="container">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="menu container">
                    <li>
                        <a href="#home">Home</a>
                    </li>
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#gallery">Gallery</a>
                    </li>
                    <li>
                        <a href="#price">Price</a>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
                <a href="daftar.php" target="_blank">
                    <button class="btn-daftar">
                        Daftar
                    </button>
                </a>

                <!-- Tombol Hamburger -->
                <button class="hamburger">
                    ☰
                </button>
            </nav>
        </div>
    </header>
    <!-- main -->
    <main>
        <!-- banner -->
        <section class="banner" id="home">
            <div class="container">
                <h1>Tirta Bugar Fitness</h1>
            </div>
        </section>
        <!-- about -->
        <section class="about" id="about">
            <div class="container">
                <h2 class="about-title">About us</h2>
                <p>Kami adalah GYM yang berlokasi di JL. Bugel Indah Raya Perumahan Bugel Indah blok A9, No. 123, Bugel, Kecamatan Karawaci, Kota Tangerang, Banten 15114 dengan harga terjangkau. Tirta Bugar Fitness dengan fasilitas yang lengkap dengan konsep kekeluargaan 
                    di Tirta Bugar Fitness memiliki layanan personal trainer bersertifikat nasional dengan harga terjangkau untuk membantu mencapai tujuan kesehatan kamu. Di sini juga terdapat cardio fitness dance yang dapat membakar kalori kamu secara maksimal </p>
            </div>
        </section>
        <!-- why choose us -->
        <section class="why-choose-us">
            <h2 class="why-title">Why Choose Us</h2>
            <div class="container">
                <!-- card why choose us -->
                <div class="card-why container">
                    <div class="card-why-img">
                        <img src="assets/instruktur.svg" alt="integrated-infrastructure">
                    </div>
                    <div>
                        <h3 class="why-card-title" style="margin-top: 24px;">Integrated Infrastructure</h3>
                    </div>
                </div>
                <div class="card-why container">
                    <div class="card-why-img">
                        <img src="assets/dumbell.svg" alt="adequate-tools">
                    </div>
                    <div>
                        <h3 class="why-card-title" style="margin-top: 58px;">Adequate Tools</h3>
                    </div>
                </div>
                <div class="card-why container">
                    <div class="card-why-img">
                        <img src="assets/location.svg" alt="strategic-location">
                    </div>
                    <div>
                        <h3 class="why-card-title" style="margin-top: 4px;">Strategic Location</h3>
                    </div>
                </div>
                <div class="card-why container">
                    <div class="card-why-img">
                        <img src="assets/affordable-icon 1.svg" alt="affordable-cost">
                    </div>
                    <div>
                        <h3 class="why-card-title" style="margin-top: 22px;">Affordable Cost</h3>
                    </div>
                </div>
            </div>
        </section>
        <!-- gallery -->
        <section class="gallery" id="#gallery">
            <h2 class="gallery-title">Gallery</h2>
            <div class="container">
                <div class="gallery-img">
                    <img src="assets/1.jpg" alt="gallery-1">
                </div>
                <div class="gallery-img">
                    <img src="assets/2.jpg" alt="gallery-2">
                </div>
                <div class="gallery-img">
                    <img src="assets/3.jpg" alt="gallery-3">
                </div>
                <div class="gallery-img">
                    <img src="assets/4.jpg" alt="gallery-4">
                </div>
                <div class="gallery-img">
                    <img src="assets/5.jpeg" alt="gallery-5">
                </div>
                <div class="gallery-img">
                    <img src="assets/6.jpeg" alt="gallery-6">
                </div>
            </div>
        </section>
        <!-- price -->
        <section class="price" id="#price">
            <h2 class="price-title">Price</h2>
            <div class="container">
                <div class="card-price">
                    <div class="container">
                        <h3 class="price-card-title">Rp 100.000,00</h3>
                        <ul class="list-price-fasility">
                            <li>Fitness selama 1 bulan</li>
                            <li>8x Pertemuan</li>
                        </ul>
                        <button class="btn-buy" onclick="directDaftarMenu(1)">Buy Now</button>
                    </div>
                </div>
                <div class="card-price">
                    <div class="container">
                        <h3 class="price-card-title">Rp 185.000,00</h3>
                        <ul class="list-price-fasility">
                            <li>Fitness selama 1 bulan</li>
                            <li>Bebas Datang</li>
                        </ul>
                        <button class="btn-buy" onclick="directDaftarMenu(2)">Buy Now</button>
                    </div>
                </div>
                <div class="card-price">
                    <div class="container">
                        <h3 class="price-card-title">Rp 500.000,00</h3>
                        <ul class="list-price-fasility">
                            <li>Fitness selama 3 bulan</li>
                            <li>Bebas Datang</li>
                        </ul>
                        <button class="btn-buy" onclick="directDaftarMenu(3)">Buy Now</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- footer -->
    <footer>
        <div>
            <div class="kontak container" id="contact">
                <!-- contact -->
                <div class="contact">
                    <h2 class="title-contact">Our Address</h2>
                    <p>JL. Bugel Indah Raya Perumahan Bugel Indah blok A9, No. 123, Bugel, Kecamatan Karawaci, Kota Tangerang, Banten 15114</p>
                    <div class="social-media">
                        <div class="whatsapp-icon container">
                            <img src="assets/tb-whatsapp.svg" alt="whatsapp-icon">
                            <p>+62 813-9874-4672</p>
                        </div>
                        <div class="instagram-icon container">
                            <img src="assets/tb-instagram.svg" alt="instagram-icon">
                            <p>jose_rays</p>
                        </div>
                    </div>
                </div>
                <!-- google maps -->
                <div class="map">
                    <h2 class="title-location">Our Location</h2>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.614477651008!2d106.60354527453056!3d-6.182323560575348!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fedb9b713f75%3A0xc9dcb97f8ed138!2sTirta%20Bugar%20Fitness!5e0!3m2!1sid!2sid!4v1728318312548!5m2!1sid!2sid" width="600" height="450"
                    style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <p class="copyright">Copyright&copy; 2024 Tirta Bugar Fitness</p>
        </div>
    </footer>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('menu-active');
        }

        function directDaftarMenu(t) {
            return window.location.href = `daftar.php?id_paket=${t}`;
        }
    </script>
</body>
</html>