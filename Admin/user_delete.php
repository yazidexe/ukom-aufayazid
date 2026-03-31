<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$id = $_POST['id'];

mysqli_query($conn, "DELETE FROM officers WHERE id='$id'");

header("Location: users.php");
exit;
