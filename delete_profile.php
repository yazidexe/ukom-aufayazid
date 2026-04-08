<?php
session_start();
include "Admin/config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Hapus data order dan history yg berhubungan dengan user (tergantung setup foreign key, kalau restrict lebih baik dihapus manual dulu atau biarkan CASCADE jalan)
// Hapus user
$query = mysqli_query($conn, "DELETE FROM users WHERE id='$user_id'");

if ($query) {
    session_destroy();
    header("Location: index.php");
    exit;
} else {
    // Kalau gagal (mungkin ada constraint foreign key yg memblokir), redirect balik dengan pesan error
    $_SESSION['error'] = "Gagal menghapus profil. Pastikan tidak ada transaksi yang masih berjalan.";
    header("Location: profile.php");
    exit;
}
?>
