<?php
session_start();
include "../config/koneksi.php";

$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 1;

// PROSES SIMPAN EDIT DATA
if (isset($_POST['simpan'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    // Menghilangkan format teks rupiah non-angka jika ada input manual
    $harga = preg_replace('/[^0-9]/', '', $_POST['harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Proses penanganan file gambar baru jika diunggah
    $gambar_update_query = "";
    if (!empty($_FILES['gambar_baru']['name'])) {
        $filename = $_FILES['gambar_baru']['name'];
        $tempname = $_FILES['gambar_baru']['tmp_name'];
        move_uploaded_file($tempname, "../assets/img/" . $filename);
        $gambar_update_query = ", gambar='$filename'";
    }

    $update = mysqli_query($koneksi, "UPDATE produk SET 
        nama_produk='$nama_produk', 
        harga='$harga', 
        stok='$stok', 
        deskripsi='$deskripsi' 
        $gambar_update_query 
        WHERE id_produk='$id_produk'");

    if ($update) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='detail_produk.php?id=$id_produk';</script>";
        exit();
    }
}

// Ambil data produk ter-update untuk ditampilkan di form
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    // Data mockup fallback sesuaian kontent image_a349a3.png
    $produk = [
        'id_produk' => 1,
        'nama_produk' => 'T-Shirt Hawkins Lab',
        'harga' => 139000,
        'stok' => 'Tersedia',
        'deskripsi' => 'T-Shirt resmi Stranger Things bahan 100% cotton combed 30s, nyaman dipakai sehari-hari.',
        'gambar' => 'tshirt.png'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Admin</title>
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

        /* Form Layout */
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
            margin-bottom: 15px;
        }

        .image-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btn-upload {
            border: none;
            color: white;
            background-color: #b71c1c;
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
        }

        .form-side {
            flex: 1.2;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 0.95rem;
            color: #fff;
        }

        .form-control {
            background: #000;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            font-size: 1rem;
            outline: none;
        }

        .form-control:focus {
            border-color: #bf2600;
        }

        textarea.form-control {
            resize: none;
            height: 70px;
            font-size: 0.85rem;
            line-height: 1.4;
            color: #ccc;
        }

        /* Radios & Badges Status Stok */
        .status-wrapper {
            display: flex;
            gap: 15px;
            margin: 5px 0;
        }

        .status-label {
            border: 1px solid #00ff00;
            padding: 8px 20px;
            border-radius: 8px;
            color: #00ff00;
            font-size: 0.9rem;
            cursor: pointer;
            background: #000;
            display: inline-block;
        }

        .status-label.tidak-tersedia {
            border-color: #ff3333;
            color: #ff3333;
        }

        .status-wrapper input[type="radio"] {
            display: none;
        }

        /* Logika visual aktif pilihan status radio button */
        .status-wrapper input[type="radio"]:checked+.status-label {
            background: rgba(0, 255, 0, 0.15);
            font-weight: bold;
        }

        .status-wrapper input[type="radio"]#tidak_ready:checked+.status-label {
            background: rgba(255, 51, 51, 0.15);
        }

        /* Varian Ukuran */
        .size-options {
            display: flex;
            gap: 10px;
            align-items: center;
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

        .btn-add-size {
            border: 1px solid #444;
            background: #000;
            color: #fff;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .btn-simpan {
            background: #b71c1c;
            color: #fff;
            border: none;
            padding: 12px 40px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: fit-content;
            margin-top: 15px;
            transition: background 0.2s;
        }

        .btn-simpan:hover {
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
                <span>
                    <?php echo htmlspecialchars($produk['nama_produk']); ?>
                </span>
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
                    </header>

        <!-- FORM SUBMIT EDIT -->
        <form method="POST" action="" enctype="multipart/form-data" class="product-wrapper">

            <!-- SISI KIRI: INPUT GAMBAR -->
            <div class="image-side">
                <div class="image-box">
                    <img src="../assets/img/<?php echo $produk['gambar']; ?>" alt="Product Image">
                </div>
                <div class="upload-btn-wrapper">
                    <button class="btn-upload">Edit Gambar</button>
                    <input type="file" name="gambar_baru" accept="image/*">
                </div>
                <br>
                <a href="detail_produk.php?id=<?php echo $id_produk; ?>" class="back-link"><i
                        class="fa-solid fa-caret-left"></i> Back</a>
            </div>

            <!-- SISI KANAN: INPUT FIELDS -->
            <div class="form-side">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control"
                        value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <!-- Menampilkan nilai asli angka database, format RP diatur via CSS placeholder / input manual bebas -->
                    <input type="text" name="harga" class="form-control"
                        value="RP <?php echo number_format($produk['harga'], 0, ',', '.'); ?>" required>
                </div>

                <div class="form-group">
                    <div class="status-wrapper">
                        <input type="radio" name="stok" id="ready" value="Tersedia" <?php echo ($produk['stok'] == 'Tersedia') ? 'checked' : ''; ?>>
                        <label for="ready" class="status-label">Tersedia</label>

                        <input type="radio" name="stok" id="tidak_ready" value="Tidak tersedia" <?php echo ($produk['stok'] == 'Tidak tersedia') ? 'checked' : ''; ?>>
                        <label for="tidak_ready" class="status-label tidak-tersedia">Tidak tersedia</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"
                        required><?php echo htmlspecialchars($produk['deskripsi']); ?></textarea>
                </div>

                <div class="form-group">
                    <label style="margin-bottom: 8px; display: block;">Ukuran</label>
                    <div class="size-options">
                        <div class="size-box">S</div>
                        <div class="size-box">M</div>
                        <div class="size-box">L</div>
                        <div class="size-box">XL</div>
                        <div class="size-box">XXL</div>
                        <button type="button" class="btn-add-size"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>

                <button type="submit" name="simpan" class="btn-simpan">SIMPAN</button>
            </div>
        </form>
    </div>

</body>

</html>