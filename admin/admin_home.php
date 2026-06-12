<?php
session_start();
// KOREKSI PROTEKSI: Jika session username tidak ada, lempar ke login_admin.php (bukan login biasa!)
if (!isset($_SESSION['username'])) {
    header("Location: login_admin.php");
    exit();
}

include "../config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Home - Stranger Merch Store</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #000;
            color: white;
            padding: 20px;
        }

        .container {
            max-width: 1300px;
            margin: auto;
        }

        /* NAVBAR */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #222;
        }

        .mini-logo {
            color: #ff2a00;
            font-family: 'Cinzel Decorative', serif;
            font-size: 26px;
            font-weight: 900;
            line-height: .9;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        nav a:hover {
            color: #ff2a00;
        }

        /* HERO BANNER */
        .hero-banner {
            margin-top: 25px;
            height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ff2a00;
            border-radius: 15px;
            overflow: hidden;
            background: #050505;
        }

        .hero-side-img {
            width: 25%;
            height: 100%;
            object-fit: cover;
        }

        .hero-content {
            width: 50%;
            text-align: center;
        }

        .main-title {
            font-size: 3rem;
            color: transparent;
            -webkit-text-stroke: 2px #ff2a00;
            font-weight: 900;
        }

        .welcome-text {
            font-size: 1rem;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .hero-subtitle {
            color: #ccc;
            margin-top: 15px;
        }

        /* LAYOUT UTAMA */
        .main-layout {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }

        /* KOREKSI SIDEBAR KATEGORI (KIRI) - SAMA PERSIS SEPERTI MAKSUD ANDA */
        .sidebar {
            width: 220px;
            border: 1.5px solid #ff2a00;
            /* Border merah tipis tegas */
            padding: 25px 20px;
            border-radius: 25px;
            /* Sudut melengkung halus (Smooth Rounded) */
            background: #000;
            text-align: center;
            /* Membuat semua konten di dalam ke tengah */
            align-self: flex-start;
        }

        .sidebar-title {
            font-weight: bold;
            font-size: 0.95rem;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1.5px solid #ff2a00;
            text-transform: uppercase;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* Jarak antar menu yang renggang & rapi */
        }

        .sidebar-menu li {
            width: 100%;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: white;
            font-size: 0.95rem;
            font-weight: 500;
            display: block;
            padding: 6px 0;
            transition: color 0.2s;
        }

        /* Gaya hover opsional / penanda menu aktif jika diinginkan */
        .sidebar-menu a:hover {
            color: #ff2a00;
        }

        /* KONTEN KANAN */
        .content-area {
            flex: 1;
        }

        .section-title {
            margin-bottom: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        /* KATEGORI POPULER (KOTAK KECIL MERAPAT KE KIRI) */
        .category-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 35px;
            justify-content: flex-start;
        }

        .category-card {
            width: 140px;
            border: 1.5px solid #ff2a00;
            padding: 15px 10px;
            border-radius: 15px;
            text-align: center;
            background: #000;
        }

        .category-card img {
            width: 100%;
            height: 100px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .category-card a {
            text-decoration: none;
            color: white;
            font-size: 0.85rem;
            font-weight: bold;
        }

        /* KATEGORI TERLARIS */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .product-card {
            border: 1.5px solid #ff2a00;
            /* Border merah mengikuti tema utama figma */
            border-radius: 15px;
            padding: 12px;
            background: #000;
        }

        .product-card img {
            width: 100%;
            height: 140px;
            object-fit: contain;
            background: #000;
            border-radius: 8px;
        }

        .product-name {
            font-size: .8rem;
            margin-top: 10px;
            font-weight: bold;
            color: white;
        }

        .product-price {
            color: #ffb03a;
            margin-top: 5px;
            font-size: .8rem;
            font-weight: bold;
        }

        /* DROPDOWN PROFILE MENU */
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
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #1a1a1e;
            min-width: 160px;
            border: 1px solid #3f3f46;
            border-radius: 6px;
            z-index: 9999;
            padding: 5px 0;
            margin-top: 5px;
        }

        .user-profile-nav:hover .profile-dropdown {
            display: block;
        }

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
    </style>
</head>

<body>

    <div class="container">

        <header>
            <div class="mini-logo">
                Stranger<br>Merch Store
            </div>

            <nav>
                <ul>
                    <li><a href="admin_home.php">HOME</a></li>
                    <li><a href="dashboard.php">DASHBOARD</a></li>
                    <li><a href="produk.php">PRODUK</a></li>
                    <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
                </ul>
            </nav>

            <div class="user-profile-nav">
                <div class="profile-trigger">
                    👤 &nbsp; Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
                <div class="profile-dropdown">
                    <div class="dropdown-header">Beralih Akun:</div>
                    <a href="../auth/login.php" class="dropdown-link">▶ Pelanggan / User</a>
                    <a href="../admin/login_admin.php" class="dropdown-link">▶ Halaman Admin</a>
                    <div class="dropdown-divider"></div>
                    <a href="../auth/logout.php" class="dropdown-link text-danger">🚪 Keluar (Logout)</a>
                </div>
            </div>
        </header>

        <div class="hero-banner">
            <img src="../assets/img/elemenhome.png" class="hero-side-img">

            <div class="hero-content">
                <div class="welcome-text">WELCOME TO</div>
                <div class="main-title">
                    STRANGER<br>MERCH STORE
                </div>
                <div class="hero-subtitle">
                    Temukan merchandise eksklusif Stranger Things<br>dan tunjukkan sisi Hawkins-mu!
                </div>
            </div>

            <img src="../assets/img/elemenhome1.png" class="hero-side-img">
        </div>

        <div class="main-layout">

            <aside class="sidebar">
                <div class="sidebar-title">KATEGORI</div>
                <ul class="sidebar-menu">
                    <li><a href="produk.php?kategori=t-shirt">T-Shirt</a></li>
                    <li><a href="produk.php?kategori=hoodie">Hoodie</a></li>
                    <li><a href="produk.php?kategori=mug">Mug</a></li>
                    <li><a href="produk.php?kategori=topi">Topi</a></li>
                </ul>
            </aside>

            <div class="content-area">

                <section>
                    <div class="section-title">KATEGORI POPULER</div>
                    <div class="category-grid">
                        <div class="category-card">
                            <a href="produk.php?kategori=hoodie">
                                <img src="../assets/img/hoodie2.png">
                                <p>Hoodie</p>
                            </a>
                        </div>

                        <div class="category-card">
                            <a href="produk.php?kategori=topi">
                                <img src="../assets/img/topi1.png">
                                <p>Topi</p>
                            </a>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="section-title">KATEGORI TERLARIS</div>
                    <div class="products-grid">
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM produk LIMIT 4");
                        while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                            <div class="product-card">
                                <a href="detail_produk.php?id=<?php echo $row['id_produk']; ?>">
                                    <img src="../assets/img/<?php echo $row['gambar']; ?>">
                                </a>
                                <div class="product-name">
                                    <?php echo htmlspecialchars($row['nama_produk']); ?>
                                </div>
                                <div class="product-price">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
        </div>

    </div>

</body>

</html>