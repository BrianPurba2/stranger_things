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

$username_aktif = $_SESSION['username'];

// 4. Tangkap parameter filter status dari URL (Default: Semua Pesanan)
$status_aktif = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : 'semua';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Stranger Merch Store</title>
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

        /* ==================== CONTENT HEADER ==================== */
        .page-title {
            font-size: 1.3rem;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* Kapsul Filter Status sesuai warna di gambar Anda */
        .filter-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .tab-btn {
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 16px;
            border-radius: 20px;
            background-color: transparent;
            transition: all 0.2s;
        }

        /* Pewarnaan border khusus tiap filter status */
        .tab-semua { border: 1px solid #ffffff; color: #ffffff; }
        .tab-semua.active, .tab-semua:hover { background-color: #ffffff; color: #000000; }

        .tab-menunggu { border: 1px solid #ffaa00; color: #ffaa00; }
        .tab-menunggu.active, .tab-menunggu:hover { background-color: #ffaa00; color: #000000; }

        .tab-dikemas { border: 1px solid #e91e63; color: #e91e63; }
        .tab-dikemas.active, .tab-dikemas:hover { background-color: #e91e63; color: #ffffff; }

        .tab-dikirim { border: 1px solid #2196f3; color: #2196f3; }
        .tab-dikirim.active, .tab-dikirim:hover { background-color: #2196f3; color: #ffffff; }

        .tab-selesai { border: 1px solid #4caf50; color: #4caf50; }
        .tab-selesai.active, .tab-selesai:hover { background-color: #4caf50; color: #ffffff; }

        .tab-dibatalkan { border: 1px solid #f44336; color: #f44336; }
        .tab-dibatalkan.active, .tab-dibatalkan:hover { background-color: #f44336; color: #ffffff; }


        /* ==================== NOTA PESANAN (ORDER CARD) ==================== */
        .order-card {
            border: 1px solid #ff0000;
            border-radius: 12px;
            padding: 25px;
            background-color: rgba(5, 5, 5, 0.3);
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            gap: 30px;
        }

        /* Bagian Kiri Nota: Info Invoice */
        .order-info-side {
            width: 25%;
            display: flex;
            flex-direction: column;
            gap: 6px;
            border-right: 1px solid #222222;
            padding-right: 15px;
        }

        .order-id-label { font-size: 0.75rem; color: #888888; }
        .order-id-num { font-size: 1.2rem; font-weight: bold; color: #ffffff; letter-spacing: 0.5px; }
        .order-date-title { font-size: 0.75rem; color: #888888; margin-top: 8px; }
        .order-date-value { font-size: 0.85rem; color: #ffffff; font-weight: 500; }
        .order-total-price { font-size: 0.95rem; color: #ff0000; font-weight: bold; margin-top: 10px; }

        /* Bagian Kanan Nota: Daftar Produk Terbeli */
        .order-products-side {
            width: 75%;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Kartu Produk Kecil Horizontal */
        .prod-mini-item {
            border: 1px solid #222222;
            border-radius: 8px;
            padding: 10px;
            background-color: #000000;
            display: flex;
            align-items: center;
            gap: 12px;
            width: calc(33.33% - 10px); /* Membagi rata menjadi 3 item per baris */
            min-width: 200px;
        }

        .prod-mini-img {
            width: 50px;
            height: 50px;
            background-color: #111;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3px;
            flex-shrink: 0;
        }

        .prod-mini-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .prod-mini-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow: hidden;
        }

        .prod-mini-name {
            font-size: 0.75rem;
            font-weight: bold;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-mini-spec { font-size: 0.65rem; color: #666666; }
        .prod-mini-price { font-size: 0.7rem; color: #888888; font-weight: 500; }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            transition: color 0.2s;
            margin-top: 15px;
        }
        .back-btn:hover { color: #ff0000; }
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
                <li><a href="produk.php">PRODUK</a></li>
                <li><a href="riwayat.php" class="active">RIWAYAT PEMESANAN</a></li>
            </ul>
        </nav>
        <div class="user-menu">
            <a href="cart.php" class="cart-icon">🛒</a>
            <span>👤 Hi, <?php echo htmlspecialchars($username_aktif); ?> ▼</span>
        </div>
    </header>

    <!-- JUDUL HALAMAN -->
    <div class="page-title">Riwayat Pemesanan</div>

    <!-- TOMBOL FILTER STATUS KAPSUL -->
    <div class="filter-tabs">
        <a href="riwayat.php?status=semua" class="tab-btn tab-semua <?php echo ($status_aktif == 'semua') ? 'active' : ''; ?>">Semua Pesanan</a>
        <a href="riwayat.php?status=Diproses" class="tab-btn tab-menunggu <?php echo ($status_aktif == 'Diproses') ? 'active' : ''; ?>">Menunggu</a>
        <a href="riwayat.php?status=Dikemas" class="tab-btn tab-dikemas <?php echo ($status_aktif == 'Dikemas') ? 'active' : ''; ?>">Dikemas</a>
        <a href="riwayat.php?status=Dikirim" class="tab-btn tab-dikirim <?php echo ($status_aktif == 'Dikirim') ? 'active' : ''; ?>">Dikirim</a>
        <a href="riwayat.php?status=Selesai" class="tab-btn tab-selesai <?php echo ($status_aktif == 'Selesai') ? 'active' : ''; ?>">Selesai</a>
        <a href="riwayat.php?status=Dibatalkan" class="tab-btn tab-dibatalkan <?php echo ($status_aktif == 'Dibatalkan') ? 'active' : ''; ?>">Dibatalkan</a>
    </div>

    <!-- AREA NOTA-NOTA RIWAYAT -->
    <div class="orders-area">
        <?php
        // 5. Menyusun query database berdasarkan status filter yang sedang diklik user
        if ($status_aktif == 'semua') {
            $query_str = "SELECT * FROM pesanan WHERE username='$username_aktif' ORDER BY id_pesanan DESC";
        } else {
            $query_str = "SELECT * FROM pesanan WHERE username='$username_aktif' AND status='$status_aktif' ORDER BY id_pesanan DESC";
        }

        $query_invoice = mysqli_query($koneksi, $query_str);

        if (mysqli_num_rows($query_invoice) > 0) {
            while ($invoice = mysqli_fetch_assoc($query_invoice)) {
                $id_invoice = $invoice['id_pesanan'];
                
                // Format nomor Order ID cantik berurutan (Contoh: #ORD-0001)
                $order_id_formatted = "#ORD-" . str_pad($id_invoice, 4, "0", STR_PAD_LEFT);
            }
        }