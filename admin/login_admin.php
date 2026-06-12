<?php
session_start(); // Wajib di baris paling atas
// Menghubungkan ke database (keluar folder 'admin' dulu baru masuk ke 'config')
include "../config/koneksi.php";

// Logika pemrosesan form login admin saat tombol diklik
if (isset($_POST['login_admin'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Mengambil data dari tabel admin
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);

        // --- DISAMAKAN KUNCI SESSION-NYA DI SINI ---
        $_SESSION['username'] = $data['username']; // Diubah dari admin_username menjadi username

        // Alihkan halaman ke dashboard admin setelah sukses login
        echo "<script>
                alert('Login Berhasil! Selamat datang Admin.');
                window.location.href = 'admin_home.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Username atau Password Admin salah!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Stranger Merch Store</title>
    <!-- Font Roboto & Font Awesome untuk Ikon -->
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            /* Latar belakang menggunakan gambar badai merah kota Hawkins milik Anda */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)),
                url('../assets/img/bgloginadmin.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
            overflow-y: auto;
        }

        /* Tombol Back di Pojok Kiri Atas persis seperti di gambar */
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
            gap: 8px;
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
            margin-bottom: 35px;
            user-select: none;
            font-family: 'Cinzel Decorative', 'Georgia', serif; /* Font serif tegas bergaya retro/gothic */
            text-align: center;
        }       

        /* Mengatur baris pertama (STRANGER) dan baris kedua (MERCH STORE) */
        .brand-row-1, .brand-row-2 {
            display: block;
            position: relative;
            color: transparent;
            -webkit-text-stroke: 1.5px #e50914; /* Merah khas Netflix */
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.7), 0 0 20px rgba(229, 9, 20, 0.4);
            font-weight: 900;
        }       

        /* Baris Pertama: STRANGER */
        .brand-row-1 {
            font-size: 6.4rem;
            line-height: 1;
            letter-spacing: 6px;
            padding: 0 15px;
        }       

        /* Garis merah panjang di atas tulisan STRANGER */
        .brand-row-1::before {
            content: "";
            position: absolute;
            top: -4px; 
            left: 5px; 
            right: -2px;
            height: 5px;
            background-color: #e50914;
            box-shadow: 0 0 8px rgba(229, 9, 20, 0.8);
        }       

        /* Baris Kedua: MERCH STORE */
        .brand-row-2 {
            font-size: 3.8rem;
            line-height: 1;
            letter-spacing: 4px;
            margin-top: 5px; /* Jarak aman antar baris agar tidak tabrakan */
        }

        /* ==================== BOX ADMIN LOGIN ==================== */
        .login-box {
            width: 100%;
            max-width: 380px;
            background-color: rgba(0, 0, 0, 0.9);
            border: 1px solid #ff0000;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
            border-radius: 4px;
            padding: 40px 30px;
            text-align: center;
        }

        .login-box h2 {
            font-size: 1.4rem;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .login-box p.subtitle {
            font-size: 0.8rem;
            color: #888888;
            margin-bottom: 30px;
        }

        /* Input Group Oval Style */
        .input-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888888;
            font-size: 0.9rem;
        }

        .input-group .toggle-password {
            left: auto;
            right: 14px;
            cursor: pointer;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            background-color: transparent;
            border: 1px solid #444444;
            border-radius: 25px; /* Membuat bentuk oval melengkung sempurna */
            color: #ffffff;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-group input[type="password"], 
        .input-group input[type="text"].pass-input {
            padding-right: 40px;
        }

        .input-group input:focus {
            border-color: #ff0000;
        }

        /* Tombol Merah Solid sesuai gambar */
        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #c62828;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            transition: background-color 0.2s;
        }

        .login-btn:hover {
            background-color: #ff0000;
        }
    </style>
</head>
<body>

    <!-- TOMBOL KEMBALI KE LOGIN USER -->
    <a href="../auth/login.php" class="back-top-btn">
        <i class="fa-solid fa-caret-left"></i> ◀ Back
    </a>
    
    <div class="stranger-logo">
    <div class="brand-row-1">Stranger</div>
    <div class="brand-row-2">Merch Store</div>
    </div>

    <!-- BOX FORM LOGIN ADMIN -->
    <div class="login-box">
        <h2>ADMIN LOGIN</h2>
        <p class="subtitle">Authorized personnel only</p>

        <form action="" method="POST">
            <!-- Input Username Admin -->
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <!-- Input Password Admin -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" class="pass-input" placeholder="Password" required>
                <!-- Ikon Mata Intip Password -->
                <i class="fa-regular fa-eye-slash toggle-password" onclick="togglePass()"></i>
            </div>

            <!-- Tombol Kirim Form -->
            <button type="submit" name="login_admin" class="login-btn">Login</button>
        </form>
    </div>

    <!-- Script JavaScript untuk Tombol Mata Password -->
    <script>
        function togglePass() {
            var passwordField = document.getElementById("password");
            var icon = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>
</html>
