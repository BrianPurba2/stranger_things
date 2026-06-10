<?php
session_start();

// Proteksi login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Hubungkan ke database
include "../config/koneksi.php";

// Logika menghapus barang dari keranjang jika tombol sampah diklik
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id_hapus = $_GET['id'];
    if (isset($_SESSION['cart'][$id_hapus])) {
        unset($_SESSION['cart'][$id_hapus]);
    }
    header("Location: cart.php");
    exit;
}

// Inisialisasi awal nilai keuangan
$subtotal = 0;
$total_barang = 0;
$ongkir = 20000; // Default tarif ongkir sesuai pada gambar Anda
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Stranger Merch Store</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">
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
            color: #ff0000;
            font-size: 1.2rem;
        }

        /* ==================== TWO COLUMNS LAYOUT ==================== */
        .cart-layout {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }

        /* Kiri: Daftar Keranjang Belanja */
        .cart-left {
            width: 55%;
            border: 1px solid #ff0000;
            border-radius: 12px;
            padding: 20px;
            background-color: rgba(5, 5, 5, 0.5);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.05);
        }

        /* Kanan: Ringkasan Belanja */
        .cart-right {
            width: 45%;
            border: 1px solid #ff0000;
            border-radius: 12px;
            padding: 25px;
            background-color: rgba(5, 5, 5, 0.5);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.05);
            height: fit-content;
        }

        .section-heading {
            font-size: 1.1rem;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* ==================== LIST ITEM BELANJA ==================== */
        .cart-item {
            display: flex;
            align-items: center;
            border: 1px solid #333333;
            border-radius: 10px;
            padding: 12px;
            background-color: #000000;
            margin-bottom: 15px;
            gap: 15px;
        }

        .item-img-box {
            width: 75px;
            height: 75px;
            background-color: #111;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
        }

        .item-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .item-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .item-name {
            font-size: 0.85rem;
            font-weight: bold;
            color: #ffffff;
        }

        .item-spec {
            font-size: 0.75rem;
            color: #888888;
        }

        /* Kontrol Jumlah di dalam Item */
        .qty-controls {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }

        .qty-btn {
            background-color: transparent;
            border: 1px solid #333333;
            color: #ffffff;
            width: 28px;
            height: 25px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .qty-btn.minus { border-top-left-radius: 4px; border-bottom-left-radius: 4px; }
        .qty-btn.plus { border-top-right-radius: 4px; border-bottom-right-radius: 4px; }

        .qty-num {
            width: 35px;
            height: 25px;
            background-color: transparent;
            border-top: 1px solid #333333;
            border-bottom: 1px solid #333333;
            border-left: none;
            border-right: none;
            color: #ffffff;
            text-align: center;
            font-size: 0.8rem;
        }

        /* Tombol Tong Sampah */
        .delete-item-btn {
            background-color: transparent;
            border: 1px solid #333333;
            color: #d32f2f;
            width: 32px;
            height: 32px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
        }

        .delete-item-btn:hover {
            border-color: #ff0000;
            color: #ff0000;
            background-color: rgba(255, 0, 0, 0.05);
        }

        /* ==================== RINGKASAN BELANJA INPUTS ==================== */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 0.85rem;
        }

        .summary-label { color: #888888; }
        .summary-value { color: #ffffff; }

        /* Form Melengkung Oval sesuai gambar */
        .summary-input-box {
            width: 100%;
            padding: 10px 15px;
            background-color: transparent;
            border: 1px solid #333333;
            border-radius: 20px;
            color: #ffffff;
            font-size: 0.8rem;
            outline: none;
            margin-bottom: 12px;
        }

        .summary-select-box {
            width: 100%;
            padding: 10px 15px;
            background-color: #000000;
            border: 1px solid #333333;
            border-radius: 20px;
            color: #ffffff;
            font-size: 0.8rem;
            outline: none;
            margin-bottom: 12px;
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='24' viewBox='0 0 24 24' width='24' xmlns='http://w3.org'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .cek-btn-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .cek-btn {
            background-color: #b71c1c;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 6px 20px;
            font-size: 0.8rem;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }

        .cek-btn:hover { background-color: #ff0000; }

        /* Area Total Akhir Belanja */
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #1a1a1a;
            padding-top: 20px;
            margin-bottom: 20px;
        }

        .total-title { font-size: 1.3rem; font-weight: bold; }
        .total-price { font-size: 1.3rem; color: #ff0000; font-weight: bold; }

        .checkout-btn {
            width: 100%;
            background-color: #b71c1c;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .checkout-btn:hover { background-color: #ff0000; }

        .secure-payment {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.75rem;
            color: #888888;
        }

        .secure-payment i { color: #4caf50; }

        /* Tautan Navigasi Paling Bawah */
        .bottom-nav {
            display: flex;
            gap: 30px;
            font-size: 0.85rem;
            margin-top: 10px;
        }

        .bottom-nav a {
            text-decoration: none;
            color: #ffffff;
            transition: color 0.2s;
        }

