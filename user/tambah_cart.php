<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/koneksi.php";

$id_produk = isset($_GET['id_produk']) ? $_GET['id_produk'] : '';
$qty = isset($_GET['qty']) ? (int) $_GET['qty'] : 1;
$ukuran = isset($_GET['ukuran']) ? $_GET['ukuran'] : '-';

if (!empty($id_produk)) {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM produk WHERE id_produk='$id_produk'"
    );

    $produk = mysqli_fetch_assoc($query);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $cart_item_id = $id_produk . "_" . $ukuran;

    if (isset($_SESSION['cart'][$cart_item_id])) {

        $_SESSION['cart'][$cart_item_id]['qty'] += $qty;

    } else {

        $_SESSION['cart'][$cart_item_id] = [

            'id_produk' => $produk['id_produk'],
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'gambar' => $produk['gambar'],
            'qty' => $qty,
            'ukuran' => $ukuran

        ];
    }
}

header("Location: cart.php");
exit;
?>