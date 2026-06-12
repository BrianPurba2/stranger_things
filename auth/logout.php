<?php
// 1. Jalankan session
session_start();

// 2. Hapus semua data session yang tersimpan
$_SESSION = array();

// 3. Hancurkan session dari server
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

// 4. Lempar kembali user langsung ke halaman login utama
header("location: login.php");
exit;
?>