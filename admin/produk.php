<?php
// 1. Memulai session untuk mengecek login user
session_start();

// 2. Proteksi Halaman: Jika belum login, tendang kembali ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 3. Hubungkan ke database
include "../config/koneksi.php";

// 4. Tangkap parameter kategori dari URL. Jika tidak ada, default-nya menampilkan 'tshirt'
$kategori_aktif = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : 't-shirt';

// 5. Konversi kata kunci URL menjadi teks judul halaman yang rapi
$judul_halaman = "";
if ($kategori_aktif == 't-shirt')
    $judul_halaman = "T-SHIRT";
elseif ($kategori_aktif == 'hoodie')
    $judul_halaman = "HOODIE";
elseif ($kategori_aktif == 'mug')
    $judul_halaman = "MUG";
elseif ($kategori_aktif == 'topi')
    $judul_halaman = "TOPI";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk <?php echo $judul_halaman; ?> - Stranger Merch Store</title>
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

        .container {
            width: 100%;
            max-width: 1200px;
            background-color: #000000;
            border: 1px solid #333;
            padding: 20px;
        }

        /* NAVBAR */


        header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding-bottom:20px;
        border-bottom:1px solid #222;
        }


        .mini-logo{
        color:#ff2a00;
        font-family:'Cinzel Decorative', serif;
        font-size:26px;
        font-weight:900;
        line-height:.9;
        }


        nav ul{
        display:flex;
        list-style:none;
        gap:30px;
        }


        nav a{
        text-decoration:none;
        color:white;
        font-weight:bold;
        }


        nav a:hover{
        color:#ff2a00;
        }


        .profile{
        font-weight:bold;
        }

        /* ==================== JUDUL KATEGORI TENGAH ==================== */
        .category-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .category-header h2 {
            font-size: 1.4rem;
            letter-spacing: 4px;
            color: #ffffff;
            text-transform: uppercase;
            padding: 0 20px;
        }

        /* Garis merah dekorasi di kiri kanan judul */
        .category-header::before, .category-header::after {
            content: "";
            width: 50px;
            height: 2px;
            background-color: #ff0000;
            display: inline-block;
        }

        /* ==================== MAIN LAYOUT ==================== */
        .main-layout {
            display: flex;
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
            gap: 12px;
        }

        .sidebar-menu li a {
            display: block;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            padding: 10px;
            text-align: center;
            border-radius: 20px;
            transition: all 0.3s;
        }

        /* Efek Tombol Kategori Aktif Merah Solid sesuai gambar */
        .sidebar-menu li a.active, .sidebar-menu li a:hover {
            background-color: #b71c1c;
            color: #ffffff;
            font-weight: bold;
            box-shadow: 0 0 8px rgba(255, 0, 0, 0.4);
        }

        /* Area Grid Produk */
        .products-area {
            width: 80%;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 Kolom per baris sesuai desain */
            gap: 20px;
        }

        .product-card {
            border: 1px solid #333;
            border-radius: 10px;
            padding: 15px;
            background-color: #050505;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.3s;
        }

        .product-card:hover {
            border-color: #ff0000;
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            background-color: #111;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .product-name {
            font-size: 0.8rem;
            font-weight: bold;
            color: #dddddd;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 0.8rem;
            color: #ffaa00;
            font-weight: bold;
            margin-bottom: 10px;
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

        .buy-link {
            text-decoration: none;
            color: #ffffff;
            font-size: 1.2rem;
            transition: color 0.2s;
        }

        .buy-link:hover {
            color: #ff0000;
        }

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
    </style>
</head>
<body>

<div class="container">
    
    <!-- NAVBAR -->
    <header>
        <div class="mini-logo">Stranger<br>Merch Store</div>
        <nav>
            <ul>
                <li><a href="admin_home.php">HOME</a></li>
                 <li><a href="dashboard.php"></a></li>
                <li><a href="produk.php" class="active">PRODUK</a></li>
                <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
            </ul>
        </nav>
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

    <!-- JUDUL KATEGORI DI TENGAH -->
    <div class="category-header">
        <h2><?php echo $judul_halaman; ?></h2>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="main-layout">
        
        <!-- SIDEBAR MENU KATEGORI -->
        <aside class="sidebar">
            <div class="sidebar-title">KATEGORI</div>
            <ul class="sidebar-menu">
                <!-- Class 'active' akan berpindah otomatis berdasar parameter URL -->
                <li><a href="produk.php?kategori=t-shirt" class="<?php echo ($kategori_aktif == 't-shirt') ? 'active' : ''; ?>">T-Shirt</a></li>
                <li><a href="produk.php?kategori=hoodie" class="<?php echo ($kategori_aktif == 'hoodie') ? 'active' : ''; ?>">Hoodie</a></li>
                <li><a href="produk.php?kategori=mug" class="<?php echo ($kategori_aktif == 'mug') ? 'active' : ''; ?>">Mug</a></li>
                <li><a href="produk.php?kategori=topi" class="<?php echo ($kategori_aktif == 'topi') ? 'active' : ''; ?>">Topi</a></li>
            </ul>
        </aside>

        <!-- AREA KONTEN PRODUK -->
        <div class="products-area">
           <div class="products-grid">
    <?php
    // Ambil data produk berdasarkan kategori
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE kategori='$kategori_aktif'");

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