<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ==========================
   HAPUS ITEM
========================== */
if (isset($_GET['hapus'])) {

    $index = $_GET['hapus'];

    if (isset($_SESSION['cart'][$index])) {

        unset($_SESSION['cart'][$index]);

        $_SESSION['cart'] =
            array_values($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit;
}

/* ==========================
   TAMBAH QTY
========================== */
if (isset($_GET['plus'])) {

    $index = $_GET['plus'];

    if (isset($_SESSION['cart'][$index])) {

        $_SESSION['cart'][$index]['qty']++;
    }

    header("Location: cart.php");
    exit;
}

/* ==========================
   KURANG QTY
========================== */
if (isset($_GET['minus'])) {

    $index = $_GET['minus'];

    if (
        isset($_SESSION['cart'][$index]) &&
        $_SESSION['cart'][$index]['qty'] > 1
    ) {

        $_SESSION['cart'][$index]['qty']--;
    }

    header("Location: cart.php");
    exit;
}

/* ==========================
   HITUNG SUBTOTAL
========================== */
$subtotal = 0;
$total_barang = 0;

foreach ($_SESSION['cart'] as $item) {

    $subtotal +=
        $item['harga'] * $item['qty'];

    $total_barang +=
        $item['qty'];
}

/* ==========================
   ONGKIR BERDASARKAN ALAMAT
========================== */
$ongkir = 0;
$alamat = '';
$pembayaran = 'COD';

if (isset($_POST['cek_ongkir'])) {

    $alamat =
        strtolower(trim($_POST['alamat']));

    $pembayaran =
        $_POST['pembayaran'];

    if (strpos($alamat, 'medan') !== false) {

        $ongkir = 10000;

    } elseif (strpos($alamat, 'binjai') !== false) {

        $ongkir = 15000;

    } elseif (strpos($alamat, 'deli serdang') !== false) {

        $ongkir = 20000;

    } elseif (strpos($alamat, 'siantar') !== false) {

        $ongkir = 25000;

    } else {

        $ongkir = 30000;
    }

    $_SESSION['ongkir'] = $ongkir;
    $_SESSION['alamat_checkout'] = $_POST['alamat'];
    $_SESSION['pembayaran_checkout'] = $pembayaran;
}

if (isset($_SESSION['ongkir'])) {
    $ongkir = $_SESSION['ongkir'];
}

if (isset($_SESSION['alamat_checkout'])) {
    $alamat = $_SESSION['alamat_checkout'];
}

if (isset($_SESSION['pembayaran_checkout'])) {
    $pembayaran =
        $_SESSION['pembayaran_checkout'];
}

$total = $subtotal + $ongkir;
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
 name="viewport"
 content="width=device-width, initial-scale=1.0">

<title>Keranjang Belanja</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#0b0b0b;
    color:#fff;
    padding:20px;
}

.container{
    max-width:1200px;
    margin:auto;
    background:#000;
    padding:25px;
}

header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding: 10px 20px;
    background-color: #121214
    margin-bottom:30px;
}

.logo{
    color:#ff3b00;
    font-size:28px;
    font-weight:bold;
    line-height:1.1;
}

nav ul{
    display:flex;
    list-style:none;
    gap:20px;
}

nav a{
    text-decoration:none;
    color:white;
    font-size:14px;
}

.cart-layout{
    display:flex;
    gap:20px;
}

.cart-left{
    width:55%;
}

.cart-right{
    width:45%;
}

.title{
    font-size:28px;
    margin-bottom:15px;
    font-weight:bold;
}

.box{
    border:1px solid #ff3b00;
    border-radius:10px;
    padding:15px;
}

.cart-item{
    display:flex;
    align-items:center;
    border:1px solid #444;
    border-radius:8px;
    padding:10px;
    margin-bottom:10px;
}

.cart-img{
    width:80px;
    height:80px;
    object-fit:contain;
}

.item-info{
    flex:1;
    margin-left:15px;
}

.item-name{
    font-size:16px;
    margin-bottom:5px;
}

.item-size{
    font-size:13px;
    color:#ccc;
}

.qty-control{
    display:flex;
    margin-top:10px;
}

.qty-control a{
    width:30px;
    height:30px;
    border:1px solid #555;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    color:white;
}

.qty-value{
    width:40px;
    height:30px;
    border-top:1px solid #555;
    border-bottom:1px solid #555;
    display:flex;
    justify-content:center;
    align-items:center;
}

.delete-btn{
    color:red;
    text-decoration:none;
    font-size:20px;
}

.summary-row{
    border:1px solid #444;
    border-radius:10px;
    padding:12px;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.input{
    width:100%;
    background:transparent;
    border:none;
    color:white;
    outline:none;
}

.select{
    width:100%;
    background:black;
    color:white;
    border:none;
    outline:none;
}

.cek-wrap{
    text-align:right;
    margin-bottom:20px;
}

.cek-btn{
    background:#d52b00;
    border:none;
    color:white;
    padding:8px 20px;
    cursor:pointer;
}

.cek-btn:hover{
    background:#ff3b00;
}

.total-area{
    display:flex;
    justify-content:space-between;
    margin:30px 0;
    font-size:28px;
    font-weight:bold;
}

.total-harga{
    color:#ff0000;
}

.checkout-btn{
    width:100%;
    background:#d52b00;
    border:none;
    color:white;
    padding:15px;
    border-radius:5px;
    font-size:16px;
    cursor:pointer;
}

.checkout-btn:hover{
    background:#ff3b00;
}

.bottom-nav{
    margin-top:20px;
    display:flex;
    gap:30px;
}

.bottom-nav a{
    color:white;
    text-decoration:none;
}

.empty{
    text-align:center;
    padding:50px;
    color:#888;
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

    <header>

        <div class="logo">
            STRANGER<br>
            MERCH STORE
        </div>

        <nav>
            <ul>
                <li><a href="home.php">HOME</a></li>
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

    <div class="cart-layout">

        <!-- KIRI -->
        <div class="cart-left">

            <div class="title">
                KERANJANG BELANJA
            </div>

            <div class="box">

                <?php if (empty($_SESSION['cart'])) { ?>

                        <div class="empty">
                            Keranjang masih kosong
                        </div>

                <?php } else { ?>

                        <?php foreach ($_SESSION['cart'] as $i => $item) { ?>

                                <div class="cart-item">

                                    <img
                                        src="../assets/img/<?php echo $item['gambar']; ?>"
                                        class="cart-img">

                                    <div class="item-info">

                                        <div class="item-name">
                                            <?php echo $item['nama_produk']; ?>
                                        </div>

                                        <div class="item-size">
                                            Ukuran :
                                            <?php echo $item['ukuran']; ?>
                                        </div>

                                        <div class="item-size">
                                            Harga :
                                            Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                        </div>

                                        <div class="qty-control">

                                            <a href="?minus=<?php echo $i; ?>">-</a>

                                            <div class="qty-value">
                                                <?php echo $item['qty']; ?>
                                            </div>

                                            <a href="?plus=<?php echo $i; ?>">+</a>

                                        </div>

                                    </div>

                                    <a
                                        class="delete-btn"
                                        href="?hapus=<?php echo $i; ?>"
                                        onclick="return confirm('Hapus produk ini?')">

                                        🗑

                                    </a>

                                </div>

                        <?php } ?>

                <?php } ?>

            </div>

        </div>

        <!-- KANAN -->
        <div class="cart-right">

            <div class="title">
                RINGKASAN BELANJA
            </div>

            <div class="box">

                <!-- FORM CEK ONGKIR -->
                <form method="POST">

                    <div class="summary-row">

                        <span>
                            Subtotal (<?php echo $total_barang; ?> Produk)
                        </span>

                        <span>
                            Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                        </span>

                    </div>

                    <div class="summary-row">

                        <input
                            type="text"
                            name="alamat"
                            class="input"
                            placeholder="Masukkan Kota / Alamat"
                            value="<?php echo $alamat; ?>"
                            required>

                    </div>

                    <div class="summary-row">

                        <select
                            name="pembayaran"
                            class="select"
                            required>

                            <option value="COD"
                            <?php if ($pembayaran == "COD")
                                echo "selected"; ?>>
                                COD
                            </option>

                            <option value="QRIS"
                            <?php if ($pembayaran == "QRIS")
                                echo "selected"; ?>>
                                QRIS
                            </option>

                        </select>

                    </div>

                <div class="summary-row">

                    <span>Ongkir</span>

                    <span>
                        Rp <?php echo number_format($ongkir, 0, ',', '.'); ?>
                    </span>

                </div>

                <div class="cek-wrap">
                    <button
                        type="submit"
                        name="cek_ongkir"
                        class="cek-btn">
                        CEK ONGKIR
                    </button>
                </div>
                </form>
                <div class="total-area">

                    <div>
                        Total
                    </div>

                    <div class="total-harga">

                        Rp <?php echo number_format($total, 0, ',', '.'); ?>

                    </div>

                </div>

                <!-- FORM CHECKOUT -->
                <form
                    action="proses_checkout.php"
                    method="POST">

                    <input
                        type="hidden"
                        name="alamat"
                        value="<?php echo $alamat; ?>">

                    <input
                        type="hidden"
                        name="pembayaran"
                        value="<?php echo $pembayaran; ?>">

                    <input
                        type="hidden"
                        name="ongkir"
                        value="<?php echo $ongkir; ?>">

                    <button
                        type="submit"
                        class="checkout-btn"

                        <?php
                        if (
                            empty($_SESSION['cart']) ||
                            empty($alamat)
                        ) {
                            echo "disabled";
                        }
                        ?>>

                        CHECKOUT

                    </button>

                </form>

            </div>

        </div>

    </div>

    <div class="bottom-nav">

        <a href="produk.php">
            ◀ Kembali Belanja
        </a>

        <a href="riwayat.php">
            Riwayat Pemesanan ▶
        </a>

    </div>

</div>

</body>
</html>