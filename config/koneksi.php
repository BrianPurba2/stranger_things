<?php
// Konfigurasi server database Anda
$host = "localhost";     // Nama host server (default: localhost)
$user = "root";          // Username database (default XAMPP: root)
$password = "";              // Password database (default XAMPP: dikosongkan)
$database = "stranger_store"; // PASTIKAN nama database di phpMyAdmin Anda adalah 'stranger_store'

// Melakukan koneksi ke database server
$koneksi = mysqli_connect($host, $user, $password, $database);

// Memeriksa apakah koneksi berhasil atau gagal
if (mysqli_connect_errno()) {
    die("Koneksi database Anda gagal! Silakan periksa XAMPP Anda: " . mysqli_connect_error());
}

// Mengatur zona waktu default agar pencatatan tanggal pesanan di nota akurat
date_default_timezone_set('Asia/Jakarta');
?>
