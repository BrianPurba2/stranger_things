<?php
session_start();

// Proteksi login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Tangkap data dari URL
$id_produk = isset($_GET['id']) ? $_GET['id'] : '';
$qty = isset($_GET['qty']) ? (int) $_GET['qty'] : 1;
$ukuran = isset($_GET['ukuran']) ? $_GET['ukuran'] : '-';

if (!empty($id_produk)) {
    // Membuat keranjang kosong di session jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Membuat ID unik untuk item di keranjang berdasarkan gabungan ID Produk dan Ukuran
    // Hal ini agar produk yang sama dengan ukuran berbeda dipisah barisnya
    $cart_item_id = $id_produk . "_" . $ukuran;

    if (isset($_SESSION['cart'][$cart_item_id])) {
        // Jika barang dengan ukuran yang sama sudah ada di keranjang, tambahkan jumlahnya
        $_SESSION['cart'][$cart_item_id]['qty'] += $qty;
    } else {
        // Jika barang baru, masukkan data awalnya ke keranjang
        $_SESSION['cart'][$cart_item_id] = [
            'id_produk' => $id_produk,
            'qty' => $qty,
            'ukuran' => $ukuran
        ];
    }
}

// Setelah berhasil memasukkan ke keranjang, alihkan halaman langsung ke cart.php
header("Location: cart.php");
exit;
