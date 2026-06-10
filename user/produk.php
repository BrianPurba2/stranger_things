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
$kategori_aktif = isset($_GET['kategori']) ? mysqli_real_escape_string($koneksi, $_GET['kategori']) : 'tshirt';

// 5. Konversi kata kunci URL menjadi teks judul halaman yang rapi
$judul_halaman = "";
if ($kategori_aktif == 'tshirt') $judul_halaman = "T-SHIRT";
elseif ($kategori_aktif == 'hoodie') $judul_halaman = "HOODIE";
elseif ($kategori_aktif == 'mug') $judul_halaman = "MUG";
elseif ($kategori_aktif == 'topi') $judul_halaman = "TOPI";
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

        /* ==================== NAVBAR ==================== */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 25px;
            border-bottom: 1px solid #1a1a1a;
            margin-bottom: 30px;
        }

        .mini-logo {
            color: #ff0000;
            font-weight: 900;
            font-size: 1.2rem;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
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

        nav ul li a:hover, nav ul li a.active {
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
    </style>
</head>
<body>

<div class="container">
    
    <!-- NAVBAR -->
    <header>
        <div class="mini-logo">Stranger<br>Merch Store</div>
        <nav>
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="produk.php" class="active">PRODUK</a></li>
                <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
            </ul>
        </nav>
        <div class="user-menu">
            <a href="cart.php" class="cart-icon">🛒</a>
            <span>👤 Hi, <?php echo htmlspecialchars($_SESSION['username']); ?> ▼</span>
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
                <li><a href="produk.php?kategori=tshirt" class="<?php echo ($kategori_aktif == 'tshirt') ? 'active' : ''; ?>">T-Shirt</a></li>
                <li><a href="produk.php?kategori=hoodie" class="<?php echo ($kategori_aktif == 'hoodie') ? 'active' : ''; ?>">Hoodie</a></li>
                <li><a href="produk.php?kategori=mug" class="<?php echo ($kategori_aktif == 'mug') ? 'active' : ''; ?>">Mug</a></li>
                <li><a href="produk.php?kategori=topi" class="<?php echo ($kategori_aktif == 'topi') ? 'active' : ''; ?>">Topi</a></li>
            </ul>
        </aside>

        <!-- AREA KONTEN PRODUK -->
        <div class="products-area">
            <div class="products-grid">
                
                <?php
                // Mengambil produk berdasarkan kategori yang sedang dipilih di URL
                // Catatan: Pastikan kolom kategori di tabel database Anda diisi dengan string 'tshirt', 'hoodie', 'mug', atau 'topi'
                $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE kategori='$kategori_aktif'");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <div class="product-card">
                        <!-- Gambar dinamis dari folder assets/img/ -->
                        <img src="../assets/img/<?php echo $row['gambar']; ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                        
                        <div>
                            <div class="product-name"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                            <div class="product-price">RP <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>   
                                                                                                              