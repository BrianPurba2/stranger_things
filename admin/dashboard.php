<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

include "../config/koneksi.php";

// 1. Hitung Total Pesanan
$q_pesanan = mysqli_query($koneksi, "SELECT COUNT(*) AS total_order FROM pesanan");
$r_pesanan = mysqli_fetch_assoc($q_pesanan);
$total_pesanan = $r_pesanan['total_order'] ?? 0;

// 2. Hitung Total Penjualan
$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total_idr FROM pesanan WHERE status='Selesai'");
$r_pendapatan = mysqli_fetch_assoc($q_pendapatan);
$total_pendapatan = $r_pendapatan['total_idr'] ?? 0;

// 3. Hitung Jumlah Jenis Produk Aktif
$q_produk = mysqli_query($koneksi, "SELECT COUNT(*) AS total_prod FROM produk");
$r_produk = mysqli_fetch_assoc($q_produk);
$total_produk = $r_produk['total_prod'] ?? 0;

// 4. Hitung Total Pelanggan
$q_user = mysqli_query($koneksi, "SELECT COUNT(*) AS total_cust FROM user");
$r_user = mysqli_fetch_assoc($q_user);
$total_pelanggan = $r_user['total_cust'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Stranger Merch Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&display=swap" rel="stylesheet">
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0 20px;
            border-bottom: 1px solid #222;
            margin-bottom: 20px;
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
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        nav ul li a:hover,
        nav ul li a.active {
            color: #ff2a00;
        }

        .user-profile-nav {
            position: relative;
            cursor: pointer;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            width: 180px;
            background: #111;
            border: 1px solid #333;
            border-radius: 6px;
            z-index: 999;
        }

        .user-profile-nav:hover .profile-dropdown {
            display: block;
        }

        .dropdown-link {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
        }

        .dropdown-link:hover {
            background: #222;
        }

        /* PERBAIKAN: Flexbox layout agar sidebar dan main content sejajar kiri-kanan */
        .dashboard-layout {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .sidebar {
            width: 220px;
            flex-shrink: 0;
        }

        .admin-profile {
            border: 1px solid #bf2600;
            border-radius: 8px;
            padding: 15px;
            background: #050505;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .admin-avatar {
            font-size: 30px;
        }

        .status-online {
            color: #3ac13a;
            font-size: 12px;
        }

        .menu-list {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu-item {
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
        }

        .menu-item.active {
            background: #bf2600;
        }

        .menu-item:hover {
            background: #222;
        }

        .main-content {
            flex: 1;
            border: 1px solid #bf2600;
            border-radius: 10px;
            padding: 20px;
            background: #000;
        }

        .banner-admin {
            width: 100%;
            height: 120px;
            /* Sedikit ditinggikan agar gambar proporsional */
            border: 1px solid #bf2600;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .banner-admin img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-card {
            border: 1px solid #bf2600;
            border-radius: 6px;
            padding: 12px;
            background: #050505;
        }

        .stat-card h4 {
            font-size: 10px;
            margin: 0;
            text-transform: uppercase;
            color: #aaa;
        }

        .stat-card p {
            margin: 6px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .stat-card span {
            color: #888;
            font-size: 11px;
        }

        .data-section {
            display: flex;
            gap: 15px;
        }

        .table-container {
            flex: 1.8;
            border: 1px solid #bf2600;
            border-radius: 6px;
            padding: 15px;
            background: #050505;
        }

        .best-seller-box {
            flex: 1.2;
            border: 1px solid #bf2600;
            border-radius: 6px;
            padding: 15px;
            background: #050505;
        }

        .table-container h3,
        .best-seller-box h3 {
            margin-top: 0;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #bf2600;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #222;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            display: inline-block;
        }

        .status-selesai {
            color: #3ac13a;
            border: 1px solid #3ac13a;
        }

        .status-dikirim {
            color: #4a7dff;
            border: 1px solid #4a7dff;
        }

        .status-proses {
            color: #ff44ff;
            border: 1px solid #ff44ff;
        }

        .status-menunggu {
            color: #ffb03a;
            border: 1px solid #ffb03a;
        }

        .status-dibatalkan {
            color: #ff4444;
            border: 1px solid #ff4444;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .item-card {
            border: 1px solid #bf2600;
            border-radius: 6px;
            text-align: center;
            padding: 10px;
            background: #000;
        }

        .item-card img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .item-card p {
            font-size: 10px;
            margin: 5px 0;
        }

        .item-cart-btn {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">

        <header>
            <div class="mini-logo">Stranger<br>Merch Store</div>
            <nav>
                <ul>
                    <li><a href="admin_home.php">HOME</a></li>
                    <li><a href="produk.php">PRODUK</a></li>
                    <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
                </ul>
            </nav>
            <div class="user-profile-nav">
                <div class="profile-trigger">
                    👤 &nbsp; Hi,
                    <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?> <i
                        class="fa-solid fa-caret-down"></i>
                </div>
                <div class="profile-dropdown">
                    <div class="dropdown-header" style="padding: 10px; color: #888; font-size: 12px;">Beralih Akun:
                    </div>
                    <a href="../auth/login.php" class="dropdown-link">▶ Pelanggan / User</a>
                    <a href="../admin/login_admin.php" class="dropdown-link">▶ Halaman Admin</a>
                    <div class="dropdown-divider" style="border-top: 1px solid #333;"></div>
                    <a href="../auth/logout.php" class="dropdown-link" style="color: #ff4444;">🚪 Keluar (Logout)</a>
                </div>
            </div>
        </header>

        <div class="dashboard-layout">
            <aside class="sidebar">
                <div class="admin-profile">
                    <div class="admin-avatar"><i class="fa-regular fa-user"></i></div>
                    <div>
                        <strong style="font-size: 0.95rem; display:block; letter-spacing: 0.5px;">Admin Hawkins</strong>
                        <span style="font-size: 0.8rem; color:#888;">Administrator</span>
                        <div class="status-online"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Online
                        </div>
                    </div>
                </div>

                <div class="menu-list">
                    <span
                        style="color:#fff; font-size:0.9rem; font-weight:bold; margin: 10px 0 10px 5px; letter-spacing: 0.5px;">MENU</span>
                    <a href="dashboard.php" class="menu-item active"><i class="fa-solid fa-house-chimney"></i>
                        Dashboard</a>
                    <a href="pesanan.php" class="menu-item"><i class="fa-solid fa-cart-shopping"></i> Pesanan</a>
                    <a href="../auth/logout.php" class="menu-item"
                        onclick="return confirm('Keluar dari panel admin?')"><i
                            class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>

                <a href="admin_home.php"
                    style="color:#fff; text-decoration:none; font-size:0.95rem; margin-top:20px; display:inline-block; font-weight: bold;">
                    <i class="fa-solid fa-caret-left"></i> Back
                </a>
            </aside>

            <main class="main-content">
                <div class="banner-admin">
                    <img src="../assets/img/kategoriadmin.png" alt="Welcome to Hawkins">
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Total Pesanan</h4>
                        <p><?php echo $total_pesanan; ?></p>
                        <span>Order</span>
                    </div>
                    <div class="stat-card">
                        <h4>Penjualan</h4>
                        <p>RP <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
                        <span>Total Pendapatan</span>
                    </div>
                    <div class="stat-card">
                        <h4>Produk</h4>
                        <p><?php echo $total_produk; ?></p>
                        <span>Stok Aktiv</span>
                    </div>
                    <div class="stat-card">
                        <h4>Total Pelanggan</h4>
                        <p><?php echo $total_pelanggan; ?></p>
                        <span>Pengguna</span>
                    </div>
                </div>

                <div class="data-section">
                    <div class="table-container">
                        <h3>DATA PESANAN</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Order</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q_table = mysqli_query($koneksi, "SELECT pesanan.*, user.username FROM pesanan JOIN user ON pesanan.username = user.username ORDER BY tanggal DESC LIMIT 5");
                                if ($q_table && mysqli_num_rows($q_table) > 0) {
                                    while ($row_order = mysqli_fetch_assoc($q_table)) {
                                        $status_class = "status-menunggu";
                                        if ($row_order['status'] == 'Selesai')
                                            $status_class = "status-selesai";
                                        elseif ($row_order['status'] == 'Proses' || $row_order['status'] == 'Dikemas')
                                            $status_class = "status-proses";
                                        elseif ($row_order['status'] == 'Dikirim')
                                            $status_class = "status-dikirim";
                                        elseif ($row_order['status'] == 'Dibatalkan')
                                            $status_class = "status-dibatalkan";
                                        ?>
                                        <tr>
                                            <td>#<?php echo htmlspecialchars($row_order['id_pesanan']); ?></td>
                                            <td><?php echo htmlspecialchars($row_order['username']); ?></td>
                                            <td><?php echo date('d M Y', strtotime($row_order['tanggal'])); ?></td>
                                            <td style="color: #fff;">Rp
                                                <?php echo number_format($row_order['total_harga'], 0, ',', '.'); ?></td>
                                            <td><span
                                                    class="status-badge <?php echo $status_class; ?>"><?php echo $row_order['status']; ?></span>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    // Fallback Dummy Data jika database kosong agar mirip gambar kiri
                                    echo '<tr><td>#ORD-0001</td><td>Diva Syahfita</td><td>24 Mei 2026</td><td>Rp 448.000</td><td><span class="status-badge status-selesai">Selesai</span></td></tr>';
                                    echo '<tr><td>#ORD-0002</td><td>Steve Harrington</td><td>24 Mei 2026</td><td>Rp 500.000</td><td><span class="status-badge status-dikirim">Dikirim</span></td></tr>';
                                    echo '<tr><td>#ORD-0003</td><td>Nancy Wheeler</td><td>27 Mei 2026</td><td>Rp 438.000</td><td><span class="status-badge status-proses">Dikemas</span></td></tr>';
                                    echo '<tr><td>#ORD-0005</td><td>Jonathan Byers</td><td>28 Mei 2026</td><td>Rp 386.000</td><td><span class="status-badge status-menunggu">Menunggu</span></td></tr>';
                                    echo '<tr><td>#ORD-0006</td><td>Eleven</td><td>30 Mei 2026</td><td>Rp 448.000</td><td><span class="status-badge status-dibatalkan">Dibatalkan</span></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="best-seller-box">
                        <h3>KATEGORI TERLARIS</h3>
                        <div class="items-grid">
                            <div class="item-card">
                                <img src="../assets/img/hoodie1.png" alt="Product">
                                <p style="font-weight:bold;">Hoodie Hawkins A.V. CLUB</p>
                                <span style="color:#ffb03a;">★★★★★ <span
                                        style="color:#666; font-size:0.6rem;">(112)</span></span>
                            </div>
                            <div class="item-card">
                                <img src="../assets/img/topi5.png" alt="Product">
                                <p style="font-weight:bold;">Topi Hawkins Crew</p>
                                <span style="color:#ffb03a;">★★★★★ <span
                                        style="color:#666; font-size:0.6rem;">(94)</span></span>
                            </div>
                            <div class="item-card">
                                <img src="../assets/img/kaos1.png" alt="Product">
                                <p style="font-weight:bold;">T-Shirt The Upside Down</p>
                                <span style="color:#ffb03a;">★★★★★ <span
                                        style="color:#666; font-size:0.6rem;">(231)</span></span>
                            </div>
                            <div class="item-card">
                                <img src="../assets/img/mug1.png" alt="Product">
                                <p style="font-weight:bold;">Mug Hawkins Lab</p>
                                <span style="color:#ffb03a;">★★★★★ <span
                                        style="color:#666; font-size:0.6rem;">(42)</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

    </div>

</body>

</html>