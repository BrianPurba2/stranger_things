<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Hubungkan ke Database
include "../config/koneksi.php";

// 3. Pastikan keranjang tidak kosong dan form telah dikirim
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || !isset($_POST['alamat'])) {
    header("Location: cart.php");
    exit;
}

// 4. Tangkap data dari form ringkasan belanja
$username = $_SESSION['username'];
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$pembayaran = mysqli_real_escape_string($koneksi, $_POST['pembayaran']);
$ongkir = isset($_POST['ongkir'])
    ? (int) $_POST['ongkir']
    : 0;
$subtotal = 0;

// Hitung ulang subtotal demi keamanan data sebelum disimpan
foreach ($_SESSION['cart'] as $item) {
    $id_p = mysqli_real_escape_string($koneksi, $item['id_produk']);
    $res = mysqli_query($koneksi, "SELECT harga FROM produk WHERE id_produk='$id_p'");
    if ($prod = mysqli_fetch_assoc($res)) {
        $subtotal += ($prod['harga'] * $item['qty']);
    }
}
$total_belanja = $subtotal + $ongkir;
$tanggal_pesan = date("Y-m-d H:i:s");
$status = "Diproses";

// 5. Simpan data utama ke tabel 'pesanan' (Sesuaikan nama tabel & kolom Anda jika berbeda)
$query_pesanan = mysqli_query($koneksi, "INSERT INTO pesanan (username, tanggal, alamat, metode_pembayaran, total_harga, status) 
    VALUES ('$username', '$tanggal_pesan', '$alamat', '$pembayaran', '$total_belanja', '$status')");

if ($query_pesanan) {
    // Ambil ID pesanan yang barusan terbuat otomatis
    $id_pesanan_baru = mysqli_insert_id($koneksi);

    // 6. Simpan rincian barang satu per satu ke tabel 'detail_pesanan'
    foreach ($_SESSION['cart'] as $item) {
        $id_p = mysqli_real_escape_string($koneksi, $item['id_produk']);
        $qty = $item['qty'];
        $ukuran = mysqli_real_escape_string($koneksi, $item['ukuran']);

        // Ambil harga saat ini dari database
        $res_harga = mysqli_query($koneksi, "SELECT harga FROM produk WHERE id_produk='$id_p'");
        $data_harga = mysqli_fetch_assoc($res_harga);
        $harga_satuan = $data_harga['harga'];

        mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty, ukuran, harga_satuan) 
            VALUES ('$id_pesanan_baru', '$id_p', '$qty', '$ukuran', '$harga_satuan')");
    }

    // 7. Kosongkan kembali memori keranjang belanja karena checkout sudah berhasil
    unset($_SESSION['cart']);

    // 8. Alihkan langsung ke halaman Riwayat Pemesan dengan pesan sukses
    echo "<script>
            alert('Checkout Berhasil! Pesanan Anda sedang diproses.');
            window.location.href = 'riwayat.php';
          </script>";
    exit;
} else {
    echo "<script>alert('Gagal melakukan checkout. Silakan coba kembali.'); window.location.href='cart.php';</script>";
}
?>
