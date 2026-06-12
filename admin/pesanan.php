<?php
session_start();
// Hubungkan ke database Anda (sesuaikan path jika berbeda)
include "../config/koneksi.php";

// Pastikan session admin aktif (Opsional, hapus komentar jika ingin digunakan)

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

// PROSES UPDATE STATUS JIKA FORM DISUBMIT
if (isset($_POST['update_status'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_POST['id_pesanan']);
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    $query_update = "UPDATE pesanan SET status = '$status_baru' WHERE id_pesanan = '$id_pesanan'";
    mysqli_query($koneksi, $query_update);
    
    // Refresh halaman agar perubahan langsung terlihat
    header("Location: pesanan.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesanan Admin - Stranger Merch Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #000000;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1250px;
            margin: auto;
        }
         /* ==================== NAVBAR ================= */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0 25px 0;
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
            gap: 30px;
            margin: 0;
            padding: 0;
        }
        nav ul li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: bold;
            letter-spacing: 1px;
            transition: color 0.3s;
        }
        nav ul li a.active, nav ul li a:hover {
            color: #ff2a00;
        }

        /* Dropdown Profil */
        .user-profile-nav {
            position: relative;
            cursor: pointer;
        }
        .profile-trigger {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: bold;
        }
        .profile-trigger::after {
            content: " ▼";
            font-size: 0.7rem;
            color: #aaa;
        }
        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #111;
            min-width: 180px;
            border: 1px solid #333;
            border-radius: 6px;
            z-index: 9999;
            padding: 5px 0;
            margin-top: 10px;
        }
        .user-profile-nav:hover .profile-dropdown {
            display: block;
        }
        .dropdown-header {
            padding: 8px 15px;
            font-size: 11px;
            color: #666;
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
            background-color: #222;
            color: #ff2a00;
        }
        .dropdown-divider {
            height: 1px;
            background-color: #333;
            margin: 5px 0;
        }
        .text-danger {
            color: #ff4444 !important;
        }

        /* ==================== LAYOUT UTAMA ================= */
        .dashboard-layout {
            display: flex;
            gap: 40px;
            margin-top: 10px;
            align-items: flex-start;
        }

        /* SIDEBAR (Kiri) */
        .sidebar {
            width: 220px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: bold;
            margin: 10px 0 15px 5px;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 1.2rem;
        }

        .menu-item.active {
            background-color: #9e1900;
            color: #fff;
        }

        .menu-item:hover:not(.active) {
            background-color: #111;
            color: #ff2a00;
        }

        .back-btn {
            color: #ffffff;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 20px;
            display: inline-block;
            font-weight: bold;
        }

        /* PANEL KONTEN UTAMA (Kanan) */
        .main-content {
            flex: 1;
            border: 1px solid #400a00;
            padding: 30px;
            background: #000000;
            border-radius: 10px;
        }

        .main-content h3 {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.1rem;
            letter-spacing: 1px;
            color: #ffffff;
            font-weight: bold;
        }

        /* ==================== TABEL DATA ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th {
            background-color: #9e1900;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: bold;
            font-size: 1rem;
        }

        th:first-child { border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
        th:last-child { border-top-right-radius: 6px; border-bottom-right-radius: 6px; }

        td {
            padding: 18px 12px;
            border-bottom: 1px solid #141414;
            color: #ffffff;
            vertical-align: middle;
        }

        /* Styling Dropdown Pemilih Status */
        .status-select {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            background-color: transparent;
            cursor: pointer;
            outline: none;
            width: 120px;
            text-align: center;
        }

        /* Penyesuaian skema warna dinamis dropdown berdasarkan status pilihan */
        .status-selesai { color: #26b026; border: 1px solid #26b026; background: rgba(0, 128, 0, 0.1); }
        .status-dikirim { color: #3a86ff; border: 1px solid #3a86ff; background: rgba(58, 134, 255, 0.1); }
        .status-dikemas { color: #ff00ff; border: 1px solid #ff00ff; background: rgba(255, 0, 255, 0.1); }
        .status-menunggu { color: #ffb03a; border: 1px solid #ffb03a; background: rgba(255, 176, 58, 0.1); }
        .status-dibatalkan { color: #ff3333; border: 1px solid #ff3333; background: rgba(255, 51, 51, 0.1); }

        /* Memastikan opsi di dalam dropdown tetap terbaca (latar belakang gelap) */
        .status-select option {
            background-color: #111;
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
                <li><a href="dashboard.php"></a></li>
                <li><a href="produk.php">PRODUK</a></li>
                <li><a href="riwayat.php">RIWAYAT PEMESANAN</a></li>
            </ul>
        </nav>
        
          <div class="user-profile-nav">
          <!-- Teks sapaan yang ada ikon profilnya -->
          <div class="profile-trigger">
              👤 &nbsp; Hi, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
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

    <div class="dashboard-layout">
        
        <aside class="sidebar">
            <div class="menu-list">
                <div class="menu-title">MENU</div>
                <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house-chimney"></i> Dashboard</a>
                <a href="pesanan.php" class="menu-item active"><i class="fa-solid fa-cart-shopping"></i> Pesanan</a>
                <a href="../auth/logout.php" class="menu-item" onclick="return confirm('Keluar dari panel admin?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>

            <a href="dashboard.php" class="back-btn">
                <i class="fa-solid fa-caret-left"></i> Back
            </a>
        </aside>

        <main class="main-content">
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
                    // Ambil seluruh atau limit data pesanan dari database
                    $q_pesanan = mysqli_query($koneksi, "SELECT pesanan.*, user.username FROM pesanan JOIN user ON pesanan.username = user.username ORDER BY tanggal DESC");
                    
                    if(mysqli_num_rows($q_pesanan) > 0) {
                        while ($row = mysqli_fetch_assoc($q_pesanan)) {
                            // Menentukan kelas CSS secara dinamis awal berdasarkan isi field database
                            $class_status = "status-menunggu";
                            if ($row['status'] == 'Selesai') $class_status = "status-selesai";
                            elseif ($row['status'] == 'Dikirim') $class_status = "status-dikirim";
                            elseif ($row['status'] == 'Dikemas' || $row['status'] == 'Proses') $class_status = "status-dikemas";
                            elseif ($row['status'] == 'Dibatalkan') $class_status = "status-dibatalkan";
                            ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($row['id_pesanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo date('d Mei Y', strtotime($row['tanggal'])); ?></td>
                                <td style="font-weight: bold;">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id_pesanan" value="<?php echo $row['id_pesanan']; ?>">
                                        <select name="status" class="status-select <?php echo $class_status; ?>" onchange="this.form.submit()">
                                            <option value="Selesai" <?php if($row['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                            <option value="Dikirim" <?php if($row['status'] == 'Dikirim') echo 'selected'; ?>>Dikirim</option>
                                            <option value="Dikemas" <?php if($row['status'] == 'Dikemas' || $row['status'] == 'Proses') echo 'selected'; ?>>Dikemas</option>
                                            <option value="Menunggu" <?php if($row['status'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                                            <option value="Dibatalkan" <?php if($row['status'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php 
                        }
                    } else {
                        // TAMPILAN FALLBACK TEMPLATE JIKA DATABASE MASIH KOSONG (Sama persis seperti gambar mockup)
                        $dummy_data = [
                            ['id' => 'ORD-0001', 'user' => 'Diva Syahfita', 'tgl' => '24 Mei 2026', 'total' => 'Rp 448.000', 'status' => 'Selesai', 'cls' => 'status-selesai'],
                            ['id' => 'ORD-0002', 'user' => 'Steve Harrington', 'tgl' => '24 Mei 2026', 'total' => 'Rp 500.000', 'status' => 'Dikirim', 'cls' => 'status-dikirim'],
                            ['id' => 'ORD-0003', 'user' => 'Nancy Wheeler', 'tgl' => '27 Mei 2026', 'total' => 'Rp 438.000', 'status' => 'Dikemas', 'cls' => 'status-dikemas'],
                            ['id' => 'ORD-0005', 'user' => 'Jonathan Byers', 'tgl' => '28 Mei 2026', 'total' => 'Rp 386.000', 'status' => 'Menunggu', 'cls' => 'status-menunggu'],
                            ['id' => 'ORD-0006', 'user' => 'Eleven', 'tgl' => '30 Mei 2026', 'total' => 'Rp 448.000', 'status' => 'Dibatalkan', 'cls' => 'status-dibatalkan']
                        ];

                        foreach ($dummy_data as $dummy) {
                            echo '<tr>';
                            echo '<td>#' . $dummy['id'] . '</td>';
                            echo '<td>' . $dummy['user'] . '</td>';
                            echo '<td>' . $dummy['tgl'] . '</td>';
                            echo '<td style="font-weight: bold;">' . $dummy['total'] . '</td>';
                            echo '<td>';
                            echo '  <select class="status-select ' . $dummy['cls'] . '" onchange="alert(\'Fitur ganti status berjalan aktif saat terhubung ke database!\')">';
                            echo '      <option ' . ($dummy['status'] == 'Selesai' ? 'selected' : '') . '>Selesai</option>';
                            echo '      <option ' . ($dummy['status'] == 'Dikirim' ? 'selected' : '') . '>Dikirim</option>';
                            echo '      <option ' . ($dummy['status'] == 'Dikemas' ? 'selected' : '') . '>Dikemas</option>';
                            echo '      <option ' . ($dummy['status'] == 'Menunggu' ? 'selected' : '') . '>Menunggu</option>';
                            echo '      <option ' . ($dummy['status'] == 'Dibatalkan' ? 'selected' : '') . '>Dibatalkan</option>';
                            echo '  </select>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
   </div>

</body>
</html>