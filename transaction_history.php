<?php
session_start();
include "Admin/config/database.php";

$user_id = $_SESSION['user_id'] ?? null;

if(!$user_id){
    $orders = [];
} else {
    $query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaction History</title>

<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<?php include "includes/cart_header.php"; ?>

<div class="max-w-[900px] mx-auto mt-10 space-y-6">

<div class="max-w-[900px] mx-auto mt-10 space-y-6">

<?php if(!$user_id): ?>

    <div class="text-center text-gray-500 mt-20">
        <p class="text-lg">Your transaction history hasn't appeared yet.</p>
        <p class="text-sm mt-2">Please log in first to view your shopping history.</p>
    </div>

<?php else: ?>

    <?php if(mysqli_num_rows($query) == 0): ?>

        <p class="text-center text-gray-500">Belum ada transaksi</p>

    <?php else: ?>

        <?php while($order = mysqli_fetch_assoc($query)): ?>

        <div class="bg-white p-5 rounded-xl shadow">

            <div class="flex justify-between">
                <p class="font-semibold">Order #<?= $order['id']; ?></p>
                <p class="text-sm text-gray-400"><?= $order['created_at']; ?></p>
            </div>

            <p class="text-sm mt-2">Method: <?= $order['payment_method']; ?></p>
            <p class="text-sm">Address: <?= $order['address']; ?></p>

            <p class="text-lg font-semibold text-[#0B5C4A] mt-3">
                Total: Rp <?= number_format($order['total'],0,',','.'); ?>
            </p>

        </div>

        <?php endwhile; ?>

    <?php endif; ?>

<?php endif; ?>

</div>

</div>

</body>
</html>