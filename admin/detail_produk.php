<?php
session_start();
include "../config/koneksi.php";

// Ambil ID produk dari URL, jika tidak ada set default ke 1 (atau sesuaikan)
$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 1;

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
$produk = mysqli_fetch_assoc($query);

// Jika produk tidak ditemukan di database, gunakan data fallback berdasarkan image_a354de.png
if (!$produk) {
    $produk = [
        'id_produk' => 1,
        'nama_produk' => 'T-Shirt Hawkins Lab',
        'harga' => 139000,
        'stok' => 'Tersedia',
        'deskripsi' => 'T-Shirt resmi Stranger Things bahan 100% cotton combed 30s, nyaman dipakai sehari-hari.',
        'gambar' => 'tshirt.png' // Sesuaikan path gambar Anda
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Produk - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700;900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        /* Header & Breadcrumb */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo {
            color: #ff2a00;
            font-family: 'Cinzel Decorative', Georgia, serif;
            font-size: 24px;
            font-weight: 900;
            line-height: 0.9;
            text-transform: uppercase;
        }

        .breadcrumb {
            font-size: 0.9rem;
            color: #888;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .breadcrumb a {
            color: #fff;
            text-decoration: none;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-weight: bold;
        }

        /* Main Content Layout */
        .product-wrapper {
            display: flex;
            gap: 60px;
            margin-top: 20px;
        }

        .image-side {
            flex: 1;
            max-width: 400px;
            text-align: center;
        }

        .image-box {
            border: 1px solid #bf2600;
            border-radius: 12px;
            padding: 20px;
            background: #050505;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 380px;
        }

        .image-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .info-side {
            flex: 1.2;
        }

        .info-side h1 {
            margin: 0 0 5px 0;
            font-size: 2rem;
            font-weight: normal;
        }

        .price {
            color: #ff3b00;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .rating {
            color: #ffb03a;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .rating span {
            color: #666;
            margin-left: 5px;
        }

        .status-stock {
            color: #00ff00;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .desc {
            color: #ccc;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        /* Varian Ukuran & Jumlah */
        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #fff;
        }

        .size-options {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .size-box {
            border: 1px solid #444;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
            background: #000;
        }

        .qty-wrapper {
            display: flex;
            gap: 5px;
            margin-bottom: 35px;
        }

        .qty-btn {
            border: 1px solid #444;
            background: #000;
            color: #fff;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 1rem;
        }

        .qty-input {
            border: 1px solid #444;
            background: #000;
            color: #fff;
            width: 60px;
            height: 35px;
            text-align: center;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .btn-edit {
            display: inline-block;
            background: #b71c1c;
            color: #fff;
            text-decoration: none;
            padding: 10px 35px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .btn-edit:hover {
            background: #d32f2f;
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
        cart-icon {
           text-decoration: none;
           color: #fff;
           font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <div class="logo">Stranger<br>Merch Store</div>
            <div class="breadcrumb">
                <a href="admin_home.php">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i>
                <a href="produk.php">Produk</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><?php echo htmlspecialchars($produk['nama_produk']); ?></span>
            </div>
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

        <div class="product-wrapper">
            <!-- SISI KIRI: GAMBAR -->
            <div class="image-side">
                <div class="image-box">
                    <img src="../assets/img/<?php echo $produk['gambar']; ?>" alt="Product Image">
                </div>
                <a href="produk.php" class="back-link"><i class="fa-solid fa-caret-left"></i> Back</a>
            </div>

            <!-- SISI KANAN: DATA & TOMBOL EDIT -->
            <div class="info-side">
                <h1><?php echo htmlspecialchars($produk['nama_produk']); ?></h1>
                <div class="price">RP <?php echo number_format($produk['harga'], 0, ',', '.'); ?></div>
                <div class="rating">★★★★★ <span>(75)</span></div>

                <div class="status-stock">
                    Stock <?php echo htmlspecialchars($produk['stok']); ?>
                </div>

                <div class="desc"><?php echo htmlspecialchars($produk['deskripsi']); ?></div>

                <div class="section-title">Ukuran</div>
                <div class="size-options">
                    <div class="size-box">S</div>
                    <div class="size-box">M</div>
                    <div class="size-box">L</div>
                    <div class="size-box">XL</div>
                    <div class="size-box">XXL</div>
                </div>

                <div class="section-title">Jumlah</div>
                <div class="qty-wrapper">
                    <button class="qty-btn">-</button>
                    <input type="text" class="qty-input" value="1" readonly>
                    <button class="qty-btn">+</button>
                </div>

                <!-- Link mengarah ke halaman edit dengan membawa ID parameter -->
                <a href="edit_produk.php?id=<?php echo $produk['id_produk']; ?>" class="btn-edit">EDIT</a>
            </div>
        </div>
    </div>

</body>

</html>