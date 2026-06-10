<?php
// Memulai session untuk menyimpan status login
session_start();

// Menghubungkan ke database (keluar folder 'auth' dulu baru masuk ke 'config')
include "../config/koneksi.php";

// Logika pemrosesan form login saat tombol LOGIN ditekan
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Mengambil data user berdasarkan email (Sesuaikan nama tabel & kolom jika berbeda)
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND password='$password'");

    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);

        // Menyimpan data login ke dalam session
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];

        // Alihkan halaman ke home.php yang berada di dalam folder user
        header("Location: ../user/home.php");
        exit;
    } else {
        echo "<script>alert('Email atau Password salah! Silakan coba lagi.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stranger Merch Store</title>
    <!-- Font Roboto untuk teks & Font Benguiat/Serif tebal untuk Logo -->
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
            /* Latar belakang tema badai merah menggunakan gambar bg register.png Anda */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)),
                url('../assets/img/bg register.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            overflow-y: auto;
        }

        /* ==================== STRANGER LOGO STYLE ==================== */
        .stranger-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-transform: uppercase;
            margin-bottom: 30px;
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

        /* Wrapper Input Grup dengan Ikon */
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
            border-radius: 20px; /* Membuat input melengkung oval seperti gambar */
            color: #ffffff;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.3s;
        }

        /* Khusus input password diberi ruang kanan untuk ikon mata */
        .input-group input[type="password"], 
        .input-group input[type="text"].pass-input {
            padding-right: 38px;
        }

        .input-group input:focus {
            border-color: #ff0000;
        }

        /* Tombol Login */
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

        /* Tautan Bagian Bawah */
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

        .admin-link {
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>
<body>

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

    <!-- BOX FORM LOGIN -->
    <div class="login-box">
        <h2>LOGIN</h2>
        <p class="subtitle">Welcome Back to Hawkins</p>

        <form action="" method="POST">
            <!-- Input Email -->
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <!-- Input Password -->
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" class="pass-input" placeholder="Password" required>
                <!-- Ikon Mata untuk Intip Password -->
                <i class="fa-regular fa-eye-slash toggle-password" onclick="togglePass()"></i>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <!-- Tautan Navigasi Bawah -->
        <div class="footer-links">
            <p>Belum punya akun? Daftar <a href="register.php">di sini</a></p>
            <a href="../admin/login_admin.php" class="admin-link">Masuk sebagai <span style="font-weight: bold;">ADMIN</span></a>
        </div>
    </div>

    <!-- Script JavaScript untuk Fungsi Klik Lihat Password -->
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
