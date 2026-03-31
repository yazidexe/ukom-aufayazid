<?php
session_start();
require 'config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$query = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $admin = mysqli_fetch_assoc($result);

    // sementara plain text dulu
    if ($password === $admin['password']) {
        $_SESSION['admin_login'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        header("Location: dashboard.php");
        exit;
    }
}

header("Location: login.php?error=1");
exit;

