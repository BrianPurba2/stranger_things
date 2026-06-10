<?php
// 1. Memulai session untuk mengecek login user
session_start();

// 2. Proteksi Halaman: Jika belum login, kembalikan ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 3. Hubungkan ke database
include "../config/koneksi.php";

// 4. Tangkap ID Produk dari URL, jika tidak ada kembali ke halaman produk
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id_produk = mysqli_real_escape_string($koneksi, $_GET['id']);

// 5. Ambil data spesifik produk berdasarkan ID dari database
// Catatan: Pastikan nama tabel di database Anda adalah 'produk' dan primary key-nya 'id_produk'
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id_produk'");

// Jika produk tidak ditemukan di database
if (mysqli_num_rows($query) === 0) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location.href='produk.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($query);

// Menentukan teks breadcrumb berdasarkan kategori produk secara dinamis
$kategori_label = "";
if (isset($row['kategori'])) {
    if (strtolower($row['kategori']) == 'tshirt')
        $kategori_label = "T-Shirt";
    else if (strtolower($row['kategori']) == 'hoodie')
        $kategori_label = "Hoodie";
    else if (strtolower($row['kategori']) == 'mug')
        $kategori_label = "Mug";
    else if (strtolower($row['kategori']) == 'topi')
        $kategori_label = "Topi";
    else
        $kategori_label = ucfirst($row['kategori']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail <?php echo htmlspecialchars($row['nama_produk']); ?> - Stranger Merch Store</title>
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
            margin-bottom: 20px;
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

        /* ==================== BREADCRUMB NAVIGATION ==================== */
        .breadcrumb {
            font-size: 0.85rem;
            color: #888888;
            margin-bottom: 30px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .breadcrumb a {
            color: #888888;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #ffffff;
        }

        .breadcrumb span.separator {
            color: #444444;
        }

        .breadcrumb span.current {
            color: #aaaaaa;
        }

        /* ==================== DETAIL CONTENT LAYOUT ==================== */
        .detail-layout {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* Bagian Kiri: Gambar Produk */
        .image-section {
            width: 40%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .image-box {
            border: 1px solid #ff0000;
            border-radius: 15px;
            padding: 25px;
            background-color: #050505;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 380px;
        }

        .image-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Bagian Kanan: Informasi Produk */
        .info-section {
            width: 60%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .product-price {
            font-size: 1.4rem;
            color: #ff0000;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .rating-box {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #ffaa00;
        }

        .rating-count {
            color: #666666;
        }

        .stock-status {
            font-size: 0.85rem;
            color: #4caf50; /* Warna hijau indikator stok tersedia */
            font-weight: 500;
        }

        .product-description {
            font-size: 0.9rem;
            color: #bbbbbb;
            line-height: 1.5;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .spec-item {
            font-size: 0.9rem;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .spec-label {
            display: block;
            font-size: 0.9rem;
            color: #888888;
            margin-bottom: 5px;
        }

        /* Form Kuantitas Jumlah Barang */
        .qty-title {
            font-size: 0.9rem;
            color: #888888;
            margin-bottom: 5px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 25px;
        }

        .qty-btn {
            background-color: transparent;
            border: 1px solid #333333;
            color: #ffffff;
            width: 40px;
            height: 35px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            border-color: #ff0000;
            color: #ff0000;
        }

        .qty-btn.minus {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .qty-btn.plus {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .qty-input {
            width: 60px;
            height: 35px;
            background-color: transparent;
            border-top: 1px solid #333333;
            border-bottom: 1px solid #333333;
            border-left: none;
            border-right: none;
            color: #ffffff;
            text-align: center;
            font-size: 0.9rem;
            outline: none;
        }

        /* Tombol Tambah ke Keranjang */
        .add-to-cart-btn {
            width: 100%;
            max-width: 320px;
            background-color: #b71c1c;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: background-color 0.2s;
        }

        .add-to-cart-btn:hover {
            background-color: #ff0000;
        }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            transition: color 0.2s;
            margin-top: 20px;
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

    <!-- BREADCRUMB NAVIGATION -->
    <div class="breadcrumb">
        <a href="home.php">Home</a>
        <span class="separator">⟩</span>
        <a href="produk.php?kategori=<?php echo urlencode($row['kategori']); ?>"><?php echo $kategori_label; ?></a>
        <span class="separator">⟩</span>
        <span class="current"><?php echo htmlspecialchars($row['nama_produk']); ?></span>
    </div>

    <!-- MAIN DETAIL LAYOUT -->
