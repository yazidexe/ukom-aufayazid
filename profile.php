<?php
session_start();
include "Admin/config/database.php";

// ❌ BELUM LOGIN
if(!isset($_SESSION['user_id'])){
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profile</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; }
</style>
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-md text-center">

        <!-- AVATAR -->
        <div class="w-20 h-20 mx-auto mb-4">
            <img src="assets/default-avatar.png" 
                class="w-full h-full object-cover rounded-full border-2 border-[#0B5C4A]">
        </div>

        <h2 class="text-2xl font-semibold text-[#0B5C4A] mb-2">
            You don't have a profile yet
        </h2>

        <p class="text-gray-500 mb-6 text-sm">
            Log in first so you can shop ✨
        </p>

        <div class="flex flex-col gap-3">

            <a href="login.php"
            class="block w-full bg-[#0B5C4A] text-white py-3 rounded-lg 
            hover:opacity-90 transition">
                already have an account
            </a>

            <a href="register.php"
            class="block w-full border border-[#0B5C4A] text-[#0B5C4A] py-3 rounded-lg 
            hover:bg-[#0B5C4A] hover:text-white transition">
                don't have an account yet
            </a>

        </div>

        <a href="index.php"
        class="block mt-3 text-xs text-gray-400 hover:text-[#0B5C4A] transition">
            ← Back to home
        </a>

    </div>

</div>

</body>
</html>

<?php
exit;
}
?>

<?php
$user_id = $_SESSION['user_id'];

// 🔥 DATA USER
$user = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM users WHERE id='$user_id'
"));

// 🔥 TOTAL ORDER
$totalOrder = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total FROM orders WHERE user_id='$user_id'
"))['total'];

// 🔥 TOTAL ITEM
$totalItem = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(order_items.qty) as total 
    FROM order_items 
    JOIN orders ON orders.id = order_items.order_id
    WHERE orders.user_id='$user_id'
"))['total'] ?? 0;

// 🔥 HISTORY
$history = mysqli_query($conn, "
    SELECT orders.*, products.name AS product_name, order_items.qty
    FROM orders
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON products.id = order_items.product_id
    WHERE orders.user_id='$user_id'
    ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-[900px] mx-auto mt-20">

    <!-- BACK BUTTON -->
    <a href="index.php"
    class="inline-block mb-4 text-sm text-[#0B5C4A] hover:underline">
        ← Back to Home
    </a>

    <!-- PROFILE CARD -->
    <div class="bg-white p-6 rounded-xl shadow flex items-center justify-between">

        <div class="flex items-center gap-6">

            <!-- AVATAR -->
            <div class="relative">

                <img src="<?= !empty($_SESSION['avatar']) 
                    ? '/ukom-project/'.$_SESSION['avatar'] 
                    : 'assets/default-avatar.png'; ?>" 
                class="w-20 h-20 rounded-full object-cover border">
                <!-- UPLOAD -->
                <form action="upload_avatar.php" method="POST" enctype="multipart/form-data">
                    <label class="absolute bottom-0 right-0 bg-[#0B5C4A] text-white p-1 rounded-full cursor-pointer">
                        +
                        <input type="file" name="avatar" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

            </div>

            <!-- INFO -->
            <div>
                <h2 class="text-xl font-semibold"><?= $user['name']; ?></h2>
                <p class="text-gray-500"><?= $user['email']; ?></p>
            </div>

        </div>

        <!-- LOGOUT -->
        <a href="logout.php"
        class="bg-red-500 text-white font-medium px-8 py-2 rounded-md hover:bg-red-600 transition text-sm">
            Logout
        </a>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-2 gap-4 mt-6">

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Total Order</p>
            <h2 class="text-2xl font-bold text-[#0B5C4A]">
                <?= $totalOrder; ?>
            </h2>
        </div>

        <div class="bg-white p-5 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Total Buying</p>
            <h2 class="text-2xl font-bold text-[#0B5C4A]">
                <?= $totalItem; ?>
            </h2>
        </div>

    </div>

    <!-- HISTORY -->
    <div class="mt-8 mb-8">

        <h2 class="text-lg font-semibold mb-4">History</h2>

        <div class="space-y-4">

        <?php while($row = mysqli_fetch_assoc($history)): ?>

            <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">

                <div>
                    <p class="font-semibold">
                        <?= $row['product_name']; ?>
                    </p>
                    <p class="text-sm text-gray-500">
                        Qty: <?= $row['qty']; ?>
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-sm text-gray-400">
                        <?= date('d M Y', strtotime($row['created_at'])); ?>
                    </p>
                    <p class="text-[#0B5C4A] font-semibold">
                        Rp <?= number_format($row['total'],0,',','.'); ?>
                    </p>
                </div>

            </div>

        <?php endwhile; ?>

        </div>

    </div>

</div>

</body>
</html>