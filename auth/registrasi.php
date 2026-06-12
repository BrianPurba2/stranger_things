<?php
session_start();
include "../config/koneksi.php";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        $cek_email = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar! Gunakan email lain.');</script>";
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')");
            if ($query) {
                echo "<script>
                        alert('Registrasi berhasil! Silakan login.');
                        window.location.href = 'login.php';
                      </script>";
                exit;
            } else {
                echo "<script>alert('Registrasi gagal!');</script>";
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
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)),
                url('../assets/img/bgregister1.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 40px 20px;
            position: relative;
            overflow-y: auto;
        }

        /* PERBAIKAN: Ditambahkan z-index dan display agar tombol BACK tidak tertutup dan bisa diklik */
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
            z-index: 10; /* Menaikkan lapisan tombol ke paling atas */
        }

        .back-top-btn:hover {
            color: #ff0000;
        }

        /* Container Utama */
.stranger-logo-flat-complete {
    display: flex;
    flex-direction: column;
    align-items: center; /* Memaksa semua teks otomatis rata tengah sempurna */
    background-color: transparent;
    font-family: 'Georgia', 'Times New Roman', Times, serif; /* Font Serif tebal, tajam, dan universal */
    user-select: none;
    padding: 10px 0;
    width: 100%;
    max-width: 300px; /* Batasan lebar agar tetap fit dan proporsional */
    margin: 0 auto;
}

/* BARIS 1: STRANGER */
.logo-main-title {
    font-size: 3.4rem; /* Ukuran pas, tidak kebesaran dan tidak kekecilan */
    color: transparent;
    -webkit-text-stroke: 1.5px #ff1a1a; /* Garis tepi merah menyala khas */
    text-shadow: 0 0 5px rgba(255, 26, 26, 0.7);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -1.8px; /* KUNCI UTAMA: Huruf merapat dan bergandengan rapi */
    line-height: 0.95;
    position: relative;
    padding-top: 8px; /* Memberikan ruang untuk garis merah di atasnya */
    width: 100%;
    text-align: center;
}

/* Garis Merah Panjang Tegas di Atas "STRANGER" */
.logo-main-title::before {
    content: "";
    position: absolute;
    top: 0;
    left: 4%; /* Mengatur jarak ujung garis agar sejajar rapi dengan teks */
    right: 4%;
    height: 2.5px;
    background-color: #ff1a1a;
    box-shadow: 0 0 5px rgba(255, 26, 26, 0.7);
}

/* BARIS 2: MERCH STORE */
.logo-sub-title {
    font-size: 2.6rem; /* Ukuran sub-text yang proporsional di bawah judul */
    color: transparent;
    -webkit-text-stroke: 1.2px #ff1a1a; /* Garis tepi sedikit lebih tipis agar seimbang */
    text-shadow: 0 0 4px rgba(255, 26, 26, 0.7);
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.8px; /* Merapat tipis agar teks yang panjang tidak meluber keluar */
    line-height: 1;
    margin-top: 5px; /* Jarak aman antar baris agar bebas dari tabrakan */
    width: 100%;
    text-align: center;
}

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
            position: relative;
            z-index: 5;
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

        .input-group .toggle-icon {
            left: auto;
            right: 14px;
            cursor: pointer;
            z-index: 6;
        }

        .input-group input {
            width: 100%;
            padding: 10px 15px 10px 38px;
            background-color: transparent;
            border: 1px solid #444444;
            border-radius: 20px;
            color: #ffffff;
            font-size: 0.8rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-group input[type="password"], 
        .input-group input[type="text"].pass-field {
            padding-right: 38px;
        }

        .input-group input:focus {
            border-color: #ff0000;
        }

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

        /* PERBAIKAN: Memastikan link footer memiliki z-index dan warna merah terang agar terlihat */
        .footer-links {
            margin-top: 25px;
            font-size: 0.75rem;
            color: #888888;
            position: relative;
            z-index: 6;
        }

        .footer-links a {
            color: #ff0000 !important;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- TOMBOL BACK DI POJOK KIRI ATAS -->
    <a href="login.php" class="back-top-btn">◀ Back</a>

    <div class="stranger-logo-flat-complete">
    <div class="logo-main-title">Stranger</div>
    <div class="logo-sub-title">Merch Store</div>
</div>

    <!-- BOX FORM REGISTRASI -->
    <div class="register-box">
        <h2>BUAT AKUN BARU</h2>
        <p class="subtitle">Bergabung dengan Hawkins Crew</p>

        <form action="" method="POST">
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" placeholder="Nama Lengkap" required>
            </div>

            <div class="input-group">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" class="pass-field" placeholder="Password" required>
                <i class="fa-regular fa-eye-slash toggle-icon" onclick="togglePass('password', this)"></i>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="konfirmasi_password" id="confirm_password" class="pass-field" placeholder="Konfirmasi Password" required>
                <i class="fa-regular fa-eye-slash toggle-icon" onclick="togglePass('confirm_password', this)"></i>
            </div>

            <button type="submit" name="register" class="register-btn">Register</button>
        </form>

        <!-- TAUTAN NAVIGASI BAWAH YANG SUDAH DIPERBAIKI -->
        <div class="footer-links">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
