<?php
include 'auth.php';
require '../Admin/config/database.php';

// total product
$productResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProduct = mysqli_fetch_assoc($productResult)['total'] ?? 0;

// total transaction
$transactionResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transactions");
$totalTransaction = mysqli_fetch_assoc($transactionResult)['total'] ?? 0;

// format 0001, 0002
function formatNumber($num) {
    return str_pad($num, 4, '0', STR_PAD_LEFT);
}

$officerName = $_SESSION['officer_name'] ?? 'Officer';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Officer</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- NAVBAR -->
<div class="bg-[#0B483A] rounded-b-3xl px-8 py-6 flex items-center justify-between">
    <h1 class="text-white text-2xl font-medium">
        Officer Panel - <span class="text-[#199276] font-bold"><?= htmlspecialchars($officerName); ?></span>
    </h1>

    <a href="logout.php"
    class="bg-[#199276] text-[#0B483A] px-6 py-2 rounded-br-2xl font-medium hover:opacity-90 transition">
        Logout
    </a>
</div>

<!-- MENU -->
<div class="px-8 mt-4 flex justify-center">
    <ul class="flex gap-8 text-sm text-gray-400">
        <li>
            <a href="dashboard.php"
            class="text-[#0B483A] font-semibold">
                home
            </a>
        </li>
        <li>
            <a href="products.php"
            class="hover:text-[#0B483A] transition">
                product management
            </a>
        </li>
        <li>
            <a href="reports.php"
            class="hover:text-[#0B483A] transition">
                generate reports
            </a>
        </li>
        <li>
            <a href="transactions.php"
            class="hover:text-[#0B483A] transition">
                transaction management
            </a>
        </li>
    </ul>
</div>

<!-- CONTENT -->
<div class="px-8 mt-8 flex flex-col gap-6 max-w-4xl mx-auto">

    <!-- CARD PRODUCT -->
    <div class="bg-gray-200 rounded-sm p-6 shadow">
        <div class="flex items-center gap-3 text-[#0B483A] font-semibold mb-6">
            <i data-lucide="box" class="w-6 h-6"></i>
            <span>Number of Products</span>
        </div>

        <div class="text-center">
            <h2 class="text-6xl font-extrabold text-[#0B483A]">
                <?= formatNumber($totalProduct); ?>
            </h2>
            <p class="text-gray-500 mt-2">total product</p>
        </div>
    </div>

    <!-- CARD TRANSACTION -->
    <div class="bg-[#0B483A] rounded-sm p-6 shadow">
        <div class="flex items-center gap-3 text-white font-semibold mb-6">
            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
            <span>Transaction Amount</span>
        </div>

        <div class="text-center">
            <h2 class="text-6xl font-extrabold text-white">
                <?= formatNumber($totalTransaction); ?>
            </h2>
            <p class="text-[#199276] mt-2">transaction</p>
            <p class="text-xs text-[#033328] mt-1">automatic updates</p>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>