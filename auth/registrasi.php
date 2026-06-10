<?php
// Memulai session
session_start();

// Menghubungkan ke database (keluar folder 'auth' dulu baru masuk ke 'config')
include "../config/koneksi.php";

// Logika pemrosesan form registrasi saat tombol REGISTER ditekan
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

    // 1. Validasi apakah password dan konfirmasi password sudah sama
    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        // 2. Cek apakah email sudah terdaftar di database
        $cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar! Gunakan email lain.');</script>";
        } else {
            // 3. Masukkan data user baru ke database (Sesuaikan nama tabel & kolom jika berbeda)
            // Catatan: Disarankan menggunakan password_hash demi keamanan jika sistem login Anda mendukungnya
            $query = mysqli_query($koneksi, "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')");

            if ($query) {
                echo "<script>
                        alert('Registrasi berhasil! Silakan login.');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "<script>alert('Registrasi gagal! Terjadi kesalahan pada sistem.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Stranger Merch Store</title>
    <!-- Font Roboto untuk teks umum & Font Serif tebal untuk Logo -->
    <link href="https://googleapis.com" rel="stylesheet">
    <!-- Font Awesome untuk memanggil Ikon di dalam Input -->
    <link rel="stylesheet" href="https://cloudflare.com">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            /* Latar belakang badai kota Hawkins menggunakan gambar bg register.png Anda */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)),
                url('../assets/img/bg register.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 20px 40px 20px;
            position: relative;
            overflow-y: auto;
        }

        /* Tombol Back di Pojok Kiri Atas sesuai gambar */
        .back-top-btn {
            position: absolute;
            top: 40px;
            left: 40px;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-top-btn:hover {
            color: #ff0000;
        }

        /* ==================== STRANGER LOGO STYLE ==================== */
        .stranger-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-transform: uppercase;
            margin-bottom: 25px;
            user-select: none;
        }

        .brand-row-1, .brand-row-2 {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            position: relative;
        }

        .giant-char, .normal-char, .giant-char-2, .normal-char-2 {
            color: transparent;
            -webkit-text-stroke: 1.8px #ff0000;
            text-shadow: 0 0 8px rgba(255, 0, 0, 0.6);
            font-weight: 900;
            letter-spacing: -1px;
        }

        .brand-row-1 .giant-char { font-size: 3.8rem; line-height: 0.8; }
        .brand-row-1 .normal-char { font-size: 2.4rem; line-height: 0.9; margin-top: -1px; }

        /* Garis Atas Logo */
        .brand-row-1::before {
            content: "";
            position: absolute;
            top: 4px; 
            left: 12%; 
            right: 12%;
            height: 2.5px;
            background-color: #ff0000;
            box-shadow: 0 0 6px rgba(255, 0, 0, 0.8);
        }

        .brand-row-2 { margin-top: -6px; }
        .brand-row-2 .giant-char-2 { font-size: 3.4rem; line-height: 0.8; }
        .brand-row-2 .normal-char-2 { font-size: 2.1rem; line-height: 0.9; word-spacing: 6px; }

        /* Garis Samping Kiri Kanan Baris Kedua */
        .brand-row-2::before, .brand-row-2::after {
            content: "";
            position: absolute;
            top: 5px;
            width: 18px;
            height: 2.5px;
            background-color: #ff0000;
            box-shadow: 0 0 6px rgba(255, 0, 0, 0.8);
        }
        .brand-row-2::before { left: 4%; }
        .brand-row-2::after { right: 4%; }


        /* ==================== BOX FORM REGISTRASI ==================== */
        .register-box {
            width: 100%;
            max-width: 380px;
            background-color: rgba(0, 0, 0, 0.85);
            border: 1px solid #ff0000;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
            border-radius: 4px;
            padding: 30px 25px;
            text-align: center;
        }

        .register-box h2 {
            font-size: 1.3rem;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .register-box p.subtitle {
            font-size: 0.75rem;
            color: #888888;
            margin-bottom: 25px;
        }

        /* Wrapper Input Grup */
        .input-group {
            position: relative;
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #666666;
            font-size: 0.85rem;
        }

        /* Ikon Mata di Kanan */
        .input-group .toggle-icon {
            left: auto;
            right: 14px;
            cursor: pointer;
        }

        .input-group input {
            width: 100%;
            padding: 10px 15px 10px 38px;
            background-color: transparent;
            border: 1px solid #444444;
            border-radius: 20px; /* Bentuk melengkung oval seperti gambar */
            color: #ffffff;
            font-size: 0.8rem;
            outline: none;
            transition: border-color 0.3s;
        }

        /* Beri ruang kanan pada input password untuk ikon mata */
        .input-group input[type="password"], 
        .input-group input[type="text"].pass-field {
            padding-right: 38px;
        }

        .input-group input:focus {
            border-color: #ff0000;
        }

        /* Tombol Register */
        .register-btn {
            width: 100%;
            padding: 10px;
            background-color: #b71c1c;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            transition: background-color 0.2s;
        }

        .register-btn:hover {
            background-color: #ff0000;
        }

        /* Tautan Bagian Bawah */
        .footer-links {
            margin-top: 25px;
            font-size: 0.75rem;
            color: #888888;
        }

        .footer-links a {
            color: #ff0000;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- TOMBOL BACK DI POJOK KIRI ATAS -->
    <a href="login.php" class="back-top-btn">◀ Back</a>

    <!-- LOGO STRANGER MERCH STORE -->
    <div class="stranger-logo">
        <div class="brand-row-1">
            <span class="giant-char">S</span>
            <span class="normal-char">tranger</span>
            <span class="giant-char">R</span>
        </div>
        <div class="brand-row-2">
            <span class="giant-char-2">M</span>
            <span class="normal-char-2">erch store</span>
            <span class="giant-char-2">E</span>
        </div>
    </div>

    <!-- BOX FORM REGISTRASI -->
    <div class="register-box">
        <h2>BUAT AKUN BARU</h2>
        <p class="subtitle">Bergabung dengan Hawkins Crew</p>

        <form action="" method="POST">
            <!-- Input Nama Lengkap / Username -->
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" placeholder="Nama Lengkap" required>
            </div>

            <!-- Input Email -->
            <div class="input-group">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <!-- Input Password -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" class="pass-field" placeholder="Password" required>
