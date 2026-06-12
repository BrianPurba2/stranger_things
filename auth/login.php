<?php
// 1. Memulai session untuk menyimpan status login
session_start();

// 2. Menghubungkan ke database
include "../config/koneksi.php";

// PERBAIKAN: Memastikan nama tombol submit 'login' terbaca dengan benar
if (isset($_POST['login'])) {
    // PERBAIKAN: Mengubah input email menjadi input teks biasa agar sinkron dengan form HTML di bawah
    $username_or_email = mysqli_real_escape_string($koneksi, $_POST['username_or_email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Mencari data user berdasarkan username ATAU email (agar fleksibel saat login)
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE (username='$username_or_email' OR email='$username_or_email') AND password='$password'");

    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);

        // Menyimpan data login ke dalam session utama
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];

        // Alihkan halaman ke home.php yang berada di dalam folder user
        echo "<script>
                alert('Login Berhasil! Selamat datang di Hawkins.');
                window.location.href = '../user/home.php';
              </script>";
        exit;
    } else {
        // PERBAIKAN: Menambahkan pesan jika password/username salah agar tidak kosong putih
        echo "<script>alert('Username/Email atau Password salah! Silakan coba lagi.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stranger Merch Store</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@900&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)),
                url('../assets/img/bglogin.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            overflow-y: auto;
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
    font-size: 3.6rem; /* Ukuran pas, tidak kebesaran dan tidak kekecilan */
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
        /* ==================== BOX FORM LOGIN ==================== */
        .login-box {
            width: 100%;
            max-width: 380px;
            background-color: rgba(0, 0, 0, 0.85);
            border: 1px solid #ff0000;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
            border-radius: 4px;
            padding: 35px 30px;
            text-align: center;
        }

        .login-box h2 {
            font-size: 1.6rem;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .login-box p.subtitle {
            font-size: 0.75rem;
            color: #888888;
            margin-bottom: 25px;
        }

        .input-group {
            position: relative;
            margin-bottom: 18px;
            text-align: left;
        }

        .input-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666666;
            font-size: 0.9rem;
        }

        .input-group .toggle-password {
            left: auto;
            right: 12px;
            cursor: pointer;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 38px;
            background-color: transparent;
            border: 1px solid #333333;
            border-radius: 20px;
            color: #ffffff;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #ff0000;
        }

        .login-btn {
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
            margin-top: 5px;
            transition: background-color 0.2s;
        }

        .login-btn:hover {
            background-color: #ff0000;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 0.75rem;
            color: #888888;
            line-height: 1.6;
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
    
<div class="stranger-logo-flat-complete">
    <div class="logo-main-title">Stranger</div>
    <div class="logo-sub-title">Merch Store</div>
</div>
    <!-- BOX FORM LOGIN -->
    <div class="login-box">
        <h2>LOGIN</h2>
        <p class="subtitle">Welcome Back to Hawkins</p>

        <!-- PERBAIKAN: Memastikan form melempar data menggunakan POST ke file ini sendiri -->
        <form action="" method="POST">
            <!-- Input Username / Email -->
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <!-- PERBAIKAN: Menyamakan nama input 'username_or_email' dengan variabel PHP di atas -->
                <input type="text" name="username_or_email" placeholder="Username atau Email" required>
            </div>

            <!-- Input Password -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fa-regular fa-eye-slash toggle-password" onclick="togglePass()"></i>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <div class="footer-links">
            <p>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p>
            <a href="../admin/login_admin.php" style="display:inline-block; margin-top:10px;">Masuk sebagai <span
                    style="font-weight: bold;">ADMIN</span></a>
        </div>
    </div>

    <script>
        function togglePass() {
            var x = document.getElementById("password");
            var icon = document.querySelector(".toggle-password");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>

</html>