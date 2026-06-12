<?php
// Memulai session
session_start();

// Cek login user
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 🔴 PASTIKAN BARIS INI ADA DAN SAMA PERSIS 🔴
include "../config/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Stranger Merch Store</title>
    <!-- Import Font Roboto untuk teks umum & Font Serif tebal untuk Logo -->
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #0b0b0b;
            color: #ffffff;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* Container Utama Website */
        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #000000;
            border: 1px solid #333;
            padding: 20px;
        }

        /* ==================== NAVBAR ==================== */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #1a1a1a;
        }

        .mini-logo {
          color: #ff2a00;
          font-family: 'Cinzel Decorative', Georgia, serif;
          font-size: 26px;
          font-weight: 900;
          line-height: 0.9;
          text-transform: uppercase;
          letter-spacing: 0.5px;
          -webkit-text-stroke: 0.5px #000;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        nav ul li a {
            text-decoration: none;
            color: #bbbbbb;
            font-size: 0.9rem;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a.active, nav ul li a:hover {
            color: #ff0000;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.9rem;
        }

        .cart-icon {
            text-decoration: none;
            color: #fff;
            font-size: 1.2rem;
        }

        /* ==================== HERO BANNER ==================== */
        .hero-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 2px solid #ff0000;
            border-radius: 15px;
            margin-top: 20px;
            overflow: hidden;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4));
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
            height: 320px;
        }

        .hero-side-img {
            width: 25%;
            height: 100%;
            object-fit: cover;
        }

        .hero-content {
            width: 50%;
            text-align: center;
            padding: 20px;
        }

        .welcome-text {
            font-size: 1.2rem;
            letter-spacing: 3px;
            color: #ffffff;
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Garis horizontal kiri kanan WELCOME TO */
        .welcome-text::before, .welcome-text::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40px;
            height: 2px;
            background-color: #ff0000;
        }
        .welcome-text::before { left: -50px; }
        .welcome-text::after { right: -50px; }

        /* Main Logo Stranger Style */
        .main-title {
            color: transparent;
            -webkit-text-stroke: 1.5px #ff0000;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            font-size: 2.5rem;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 15px;
        }

        .hero-subtitle {
            font-size: 0.9rem;
            color: #cccccc;
            line-height: 1.4;
        }

        /* ==================== MAIN CONTENT LAYOUT ==================== */
        .main-layout {
            display: flex;
            margin-top: 30px;
            gap: 30px;
        }

        /* Sidebar Kategori */
        .sidebar {
            width: 20%;
            border: 1px solid #ff0000;
            border-radius: 15px;
            padding: 20px;
            height: fit-content;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.1);
        }

        .sidebar-title {
            text-align: center;
            font-size: 1rem;
            padding-bottom: 10px;
            border-bottom: 1px solid #ff0000;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .sidebar-menu li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .sidebar-menu li a:hover {
            color: #ff0000;
        }

        /* Area Konten Produk */
        .content-area {
            width: 80%;
        }

        .section-title {
            font-size: 1rem;
            letter-spacing: 1px;
            margin-bottom: 15px;
            color: #ffffff;
        }

        /* Grid Kategori Populer */
        .category-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-card {
            width: 120px;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            background-color: #050505;
        }

        .category-card img {
            width: 100%;
            height: 90px;
            object-fit: contain;
        }

        .category-card p {
            font-size: 0.8rem;
            margin-top: 8px;
        }

        /* Grid Produk Terlaris */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .product-card {
            border: 1px solid #333;
            border-radius: 10px;
            padding: 12px;
            background-color: #050505;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-card img {
            width: 100%;
            height: 140px;
            object-fit: contain;
            background-color: #111;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 0.75rem;
            font-weight: bold;
            color: #dddddd;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price {
            font-size: 0.75rem;
            color: #ffaa00;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .rating {
            font-size: 0.7rem;
            color: #ffaa00;
        }

        .buy-btn {
            background: none;
            border: none;
            color: #ffffff;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .buy-btn:hover {
            color: #ff0000;
        }

        /* Tombol Back */
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .back-btn:hover {
            color: #ff0000;
        }

        /* Container Utama Menu Profil */
        .user-profile-nav {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .profile-trigger {
            color: #ffffff;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .profile-trigger:hover {
            background-color: #1a1a1e;
        }

        /* Kotak Dropdown Pilihan Menu Akun */
        .profile-dropdown {
            display: none; /* Tersembunyi secara default */
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #1a1a1e;
            min-width: 160px;
            border: 1px solid #3f3f46;
            border-radius: 6px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.6);
            z-index: 9999;
            padding: 5px 0;
            margin-top: 5px;
        }

        /* Memunculkan dropdown saat teks nama didekati kursor */
        .user-profile-nav:hover .profile-dropdown {
            display: block;
        }

        /* Gaya Teks di Dalam Dropdown */
        .dropdown-header {
            padding: 8px 15px;
            font-size: 11px;
            color: #71717a;
            text-transform: uppercase;
            font-weight: bold;
        }

        .dropdown-link {
            color: #ffffff;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 13px;
            transition: background-color 0.2s, color 0.2s;
        }

        .dropdown-link:hover {
            background-color: #2e2e35;
            color: #ff4a1c;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #3f3f46;
            margin: 5px 0;
        }

        .text-danger {
            color: #ff4444 !important;
        }

        .nav-right-group {
           display: flex;
           align-items: center;
           gap: 20px;
           font-size: 0.9rem;
        }
        .cart-icon {
           text-decoration: none;
           color: #fff;
           font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- NAVBAR -->
    <header>
        <div class="mini-logo">Stranger<br>Merch Store</div>
        <nav>
            <ul>
                <li><a href="home.php" class="active">HOME</a></li>
                <li><a href="produk.php">PRODUK</a></li>
                <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
            </ul>
        </nav>
        <div class="nav-right-group">
            <a href="cart.php" class="cart-icon">🛒</a>
        <div class="user-profile-nav">
        <!-- Teks sapaan yang ada ikon profilnya -->
        <div class="profile-trigger">
            👤 &nbsp; Hi, <?php echo $_SESSION['username']; ?>
        </div>

        <!-- Menu Pilihan Akun (Muncul Saat Di-hover/Disentuh) -->
        <div class="profile-dropdown">
            <div class="dropdown-header">Beralih Akun:</div>
            <a href="../auth/login.php" class="dropdown-link">▶ Pelanggan / User</a>
            <a href="../admin/login_admin.php" class="dropdown-link">▶ Halaman Admin</a>
            <div class="dropdown-divider"></div>
            <a href="../auth/logout.php" class="dropdown-link text-danger">🚪 Keluar (Logout)</a>
        </div>
    </div>
        </header>

    <!-- HERO BANNER -->
    <div class="hero-banner">
        <!-- Gambar Kiri (Ganti URL dengan gambar lokal Anda jika ada) -->
        <img class="hero-side-img" src="../assets/img/elemenhome.png" alt="Hawkins Sign">
        
        <div class="hero-content">
            <div class="welcome-text">WELCOME TO</div>
            <div class="main-title">STRANGER<br>MERCH STORE</div>
            <p class="hero-subtitle">Temukan merchandise eksklusif Stranger Things<br>dan tunjukkan sisi Hawkins-mu !</p>
        </div>
        
        <!-- Gambar Kanan (Ganti URL dengan gambar lokal Anda jika ada) -->
        <img class="hero-side-img" src="../assets/img/elemenhome1.png" alt="Stranger Crew">
    </div>

    <!-- MAIN LAYOUT -->
    <div class="main-layout">
        
        <!-- SIDEBAR KATEGORI -->
        <aside class="sidebar">
            <div class="sidebar-title">KATEGORI</div>
            <ul class="sidebar-menu">
                <li><a href="produk.php">T-Shirt</a></li>
                <li><a href="produk.php">Hoodie</a></li>
                <li><a href="produk.php">Mug</a></li>
                <li><a href="produk.php">Topi</a></li>
            </ul>
        </aside>

        <!-- KONTEN AREA -->
        <div class="content-area">
            
            <!-- KATEGORI POPULER -->
            <section>
                <div class="section-title">KATEGORI POPULER</div>
                <div class="category-grid">
                 <!-- Kategori 1: Hoodie -->
                    <div class="category-card">
                        <!-- Mengarah ke halaman produk kategori hoodie saat diklik -->
                        <a href="produk.php?kategori=hoodie" style="text-decoration: none; color: inherit;">
                            <img src="../assets/img/hoodie2.png" alt="Hoodie">
                            <p>Hoodie</p>
                        </a>
                    </div>
                    
                    <!-- Kategori 2: Topi -->
                    <div class="category-card">
                        <!-- Mengarah ke halaman produk kategori topi saat diklik -->
                        <a href="produk.php?kategori=topi" style="text-decoration: none; color: inherit;">
                            <img src="../assets/img/topi1.png" alt="Topi">
                            <p>Topi</p>
                        </a>
                    </div>
                </div>
            </section>

            <!-- KATEGORI TERLARIS (DINAMIS MEMANGGIL DATA DARI DATABASE) -->
            <section>
                <div class="section-title">KATEGORI TERLARIS</div>
                <div class="products-grid">
                    
                    <?php
                        // Mengambil 4 data produk teratas dari tabel database secara otomatis
                        $query = mysqli_query($koneksi, "SELECT * FROM produk LIMIT 4");

                        if (mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                <!-- Kartu Produk Dinamis -->
                                <div class="product-card">
                                    <!-- Link gambar menuju ke halaman detail produk sesuai ID-nya -->
                                    <a href="detail_produk.php?id=<?php echo $row['id_produk']; ?>">
                                        <img src="../assets/img/<?php echo $row['gambar']; ?>"
                                            alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                                    </a>
                
                                    <!-- Detail Teks Nama & Harga -->
                                    <div>
                                        <a href="detail_produk.php?id=<?php echo $row['id_produk']; ?>" style="text-decoration: none;">
                                            <div class="product-name">
                                                <?php echo htmlspecialchars($row['nama_produk']); ?>
                                            </div>
                                        </a>
                                        <div class="product-price">RP
                                            <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                        </div>
                                    </div>
                
                                    <!-- Footer Kartu: Rating & Tombol Masuk Keranjang -->
                                    <div class="product-footer">
                                        <span class="rating">⭐⭐⭐⭐⭐</span>
                                        <!-- Terintegrasi ke file tambah_cart.php dengan membawa ID parameter produk -->
                                        <a href="tambah_cart.php?id=<?php echo $row['id_produk']; ?>&qty=1" class="buy-link">🛒</a>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            // Teks pemberitahuan jika tabel produk di database phpMyAdmin Anda masih kosong
                            echo "<p style='color: #666; font-size: 0.9rem; grid-column: span 4; text-align: center; padding: 20px 0;'>
                                Belum ada data produk di database. Silakan tambah data melalui panel Admin.
                              </p>";
                        }
                        ?>
                
                    </div>
                </section>
                
                </div>
                </div>
                
                <!-- TOMBOL BACK / LOGOUT DI BAGIAN BAWAH CONTAINER -->
                <a href="../auth/logout.php" class="back-btn">◀ Logout</a>
                
                </div>
                
                </body>
                
                </html>
