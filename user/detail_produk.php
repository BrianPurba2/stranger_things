<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id_produk = mysqli_real_escape_string($koneksi, $_GET['id']);

$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id_produk'");

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Produk tidak ditemukan!');window.location='produk.php';</script>";
    exit;
}

$produk = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    Detail <?php echo htmlspecialchars($produk['nama_produk']); ?>
</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#0b0b0b;
    color:white;
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
    margin-bottom:30px;
}

.mini-logo{
    color:#ff4500;
    font-size:28px;
    font-weight:bold;
    line-height:1.1;
}

nav ul{
    list-style:none;
    display:flex;
    gap:20px;
}

nav a{
    color:#ccc;
    text-decoration:none;
}

nav a.active{
    color:#ff4500;
}

.user-menu{
    display:flex;
    gap:20px;
    align-items:center;
}

.cart-icon{
    color:white;
    text-decoration:none;
}

.breadcrumb{
    margin-bottom:30px;
    color:#999;
}

.breadcrumb a{
    color:#999;
    text-decoration:none;
}

.detail-layout{
    display:flex;
    gap:60px;
    align-items:flex-start;
}

.product-image{
    width:40%;
}

.image-box{
    border:1px solid #ff4500;
    border-radius:15px;
    padding:25px;
    text-align:center;
}

.image-box img{
    width:100%;
    max-width:300px;
    height:auto;
}

.back-btn{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:white;
}

.back-btn:hover{
    color:#ff4500;
}

.product-info{
    width:60%;
}

.product-info h1{
    font-size:42px;
    margin-bottom:10px;
}

.price{
    font-size:34px;
    color:#ff4500;
    font-weight:bold;
    margin-bottom:15px;
}

.rating{
    color:gold;
    margin-bottom:15px;
}

.stock{
    color:#00cc00;
    font-weight:bold;
    margin-bottom:15px;
}

.description{
    color:#ddd;
    line-height:1.6;
    margin-bottom:20px;
}

.spec-label{
    margin-bottom:10px;
    font-weight:bold;
}

.size-options{
    display:flex;
    gap:10px;
    margin-bottom:25px;
}

.size-options input{
    display:none;
}

.size-options span{
    display:inline-block;
    width:45px;
    height:35px;
    line-height:35px;
    text-align:center;
    border:1px solid #555;
    border-radius:8px;
    cursor:pointer;
}

.size-options input:checked + span{
    border-color:#ff4500;
    background:#1a1a1a;
}

.qty-title{
    margin-bottom:10px;
}

.quantity-control{
    display:flex;
    margin-bottom:25px;
}

.qty-btn{
    width:45px;
    height:40px;
    border:1px solid #555;
    background:#111;
    color:white;
    cursor:pointer;
    font-size:18px;
}

.qty-input{
    width:60px;
    text-align:center;
    background:#111;
    color:white;
    border:1px solid #555;
}

.add-to-cart-btn{
    background:#d52b00;
    color:white;
    border:none;
    padding:15px 25px;
    font-size:16px;
    font-weight:bold;
    border-radius:5px;
    cursor:pointer;
    min-width:300px;
}

.add-to-cart-btn:hover{
    background:#ff4500;
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

    <div class="mini-logo">
        Stranger<br>Merch Store
    </div>

    <nav>
        <ul>
            <li><a href="home.php">HOME</a></li>
            <li><a href="produk.php" class="active">PRODUK</a></li>
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

<div class="breadcrumb">
    <a href="home.php">Home</a>
    >
    <a href="produk.php">Produk</a>
    >
    <?php echo htmlspecialchars($produk['nama_produk']); ?>
</div>

<div class="detail-layout">

    <div class="product-image">

        <div class="image-box">
            <img src="../assets/img/<?php echo $produk['gambar']; ?>"
                 alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>">
        </div>

        <a href="produk.php" class="back-btn">
            ◀ Back
        </a>

    </div>

    <div class="product-info">

        <h1>
            <?php echo htmlspecialchars($produk['nama_produk']); ?>
        </h1>

        <div class="price">
            RP <?php echo number_format($produk['harga'], 0, ',', '.'); ?>
        </div>

        <div class="rating">
            ⭐⭐⭐⭐⭐ (100)
        </div>

        <div class="stock">
            Stock Tersedia
        </div>

        <div class="description">
            <?php echo htmlspecialchars($produk['deskripsi']); ?>
        </div>

        <form action="tambah_cart.php" method="GET">

            <input type="hidden"
                   name="id_produk"
                   value="<?php echo $produk['id_produk']; ?>">

            <?php
            $kategori = strtolower($produk['kategori']);

            if ($kategori == 't-shirt' || $kategori == 'hoodie' || $kategori == 'topi') {
                ?>
                    <div class="spec-label">Ukuran</div>

                    <div class="size-options">

                        <label>
                            <input type="radio" name="ukuran" value="S" checked>
                            <span>S</span>
                        </label>

                        <label>
                            <input type="radio" name="ukuran" value="M">
                            <span>M</span>
                        </label>

                        <label>
                            <input type="radio" name="ukuran" value="L">
                            <span>L</span>
                        </label>

                        <label>
                            <input type="radio" name="ukuran" value="XL">
                            <span>XL</span>
                        </label>

                        <label>
                            <input type="radio" name="ukuran" value="XXL">
                            <span>XXL</span>
                        </label>

                    </div>
            <?php } ?>
            <?php
            if($kategori == 'mug'){
            ?>
                <div class="spec-label">Ukuran</div>

                <div style="margin-bottom:20px;">
                    20 cm x 9 cm
                </div>
            <?php
            }
            ?>

            <div class="qty-title">
                Jumlah
            </div>

            <div class="quantity-control">

                <button type="button"
                        class="qty-btn"
                        id="minus">
                    -
                </button>

                <input type="text"
                       id="qty"
                       name="qty"
                       class="qty-input"
                       value="1"
                       readonly>

                <button type="button"
                        class="qty-btn"
                        id="plus">
                    +
                </button>

            </div>

            <button type="submit" class="add-to-cart-btn">
                🛒 TAMBAH KE KERANJANG
            </button>

        </form>

    </div>

</div>

</div>

<script>

const minusBtn = document.getElementById('minus');
const plusBtn = document.getElementById('plus');
const qtyInput = document.getElementById('qty');

plusBtn.addEventListener('click', function(){
    qtyInput.value = parseInt(qtyInput.value) + 1;
});

minusBtn.addEventListener('click', function(){
    if(parseInt(qtyInput.value) > 1){
        qtyInput.value = parseInt(qtyInput.value) - 1;
    }
});

</script>

</body>
</html>