<?php
session_start();
include '../Admin/config/database.php';

if (!isset($_POST['order_id']) || !isset($_POST['new_status'])) {
    header('Location: reports.php');
    exit;
}

$order_id   = intval($_POST['order_id']);
$new_status = $_POST['new_status'];

$allowed = ['processing', 'shipped', 'delivered'];
if (!in_array($new_status, $allowed)) {
    header('Location: reports.php');
    exit;
}

// Cek status saat ini — hanya boleh maju
$current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM orders WHERE id='$order_id'"));
$flow    = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3];

if (!$current || ($flow[$new_status] ?? -1) <= ($flow[$current['status']] ?? -1)) {
    header('Location: reports.php?tab=transaction');
    exit;
}

mysqli_query($conn, "UPDATE orders SET status='$new_status' WHERE id='$order_id'");

header('Location: reports.php?tab=transaction');
exit;
