<?php
// 1. Wajib memulai session sebelum bisa menghapusnya
session_start();

// 2. Mengosongkan semua variabel session yang tersimpan
$_SESSION = array();

// 3. Menghancurkan seluruh session yang aktif di sistem browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
session_destroy();

// 4. Setelah bersih, alihkan user kembali ke halaman Login dengan pesan pop-up
echo "<script>
        alert('Anda telah berhasil keluar dari Hawkins Crew!');
        window.location.href = 'login.php';
      </script>";
exit;
?>
