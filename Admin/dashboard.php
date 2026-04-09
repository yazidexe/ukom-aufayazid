<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$productResult    = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProduct     = mysqli_fetch_assoc($productResult)['total'] ?? 0;

$officerResult    = mysqli_query($conn, "SELECT COUNT(*) AS total FROM officers");
$totalOfficer     = mysqli_fetch_assoc($officerResult)['total'] ?? 0;

$orderResult      = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$totalOrder       = mysqli_fetch_assoc($orderResult)['total'] ?? 0;

$pendingResult    = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='pending'");
$totalPending     = mysqli_fetch_assoc($pendingResult)['total'] ?? 0;

$userResult       = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$totalUser        = mysqli_fetch_assoc($userResult)['total'] ?? 0;

// Total revenue
$revenueResult    = mysqli_query($conn, "SELECT SUM(total) AS revenue FROM orders WHERE status='accepted'");
$totalRevenue     = mysqli_fetch_assoc($revenueResult)['revenue'] ?? 0;

// 5 transaksi terbaru
$recentResult = mysqli_query($conn, "
    SELECT orders.id, users.name AS customer, orders.total, orders.payment_method, orders.status, orders.created_at
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Admin - Azula</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-num { animation: countUp 0.6s ease forwards; }
        .card-hover { transition: all 0.25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(11,72,58,0.12); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- NAVBAR -->
    <div class="bg-[#0B483A] rounded-b-3xl px-8 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-[#199276] rounded-xl flex items-center justify-center">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i>
            </div>
            <h1 class="text-white text-xl font-bold">Dasbor Admin</h1>
        </div>
        <a href="logout.php" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-xl font-medium transition text-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
        </a>
    </div>

    <!-- MENU -->
    <div class="px-8 mt-4 flex justify-center">
        <ul class="flex gap-8 text-sm text-gray-400">
            <li><a href="dashboard.php" class="text-[#0B483A] font-semibold">beranda</a></li>
            <li><a href="users.php" class="hover:text-[#0B483A] transition">manajemen pengguna</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
            <li><a href="backup.php" class="hover:text-[#0B483A] transition">backup data</a></li>
        </ul>
    </div>

    <!-- GREETING BANNER -->
    <div class="max-w-6xl mx-auto px-6 mt-8">
        <div class="bg-[#0B483A] rounded-2xl px-8 py-6 flex items-center justify-between overflow-hidden relative">
            <div class="absolute right-0 top-0 w-64 h-full opacity-10">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                    <circle cx="160" cy="60" r="100" fill="white"/>
                </svg>
            </div>
            <div class="relative z-10">
                <p class="text-[#199276] text-sm font-medium">Selamat datang kembali 👋</p>
                <h2 class="text-white text-2xl font-bold mt-1">Administrator</h2>
                <p class="text-white/50 text-sm mt-1"><?= date('l, d F Y'); ?> · <?= date('H:i'); ?> WIB</p>
            </div>
            <div class="relative z-10 flex items-center gap-3">
                <?php if($totalPending > 0): ?>
                <div class="bg-yellow-400 text-yellow-900 px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-bold">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <?= $totalPending; ?> Pesanan Pending
                </div>
                <?php endif; ?>
                <a href="reports.php?tab=transaction" class="bg-[#199276] text-white px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 hover:bg-[#148066] transition">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i> Lihat Transaksi
                </a>
            </div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="max-w-6xl mx-auto px-6 mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Produk -->
        <a href="products.php" class="card-hover bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-[#0B483A]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="package" class="w-6 h-6 text-[#0B483A]"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800 stat-num" data-target="<?= $totalProduct; ?>"><?= $totalProduct; ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Total Produk</p>
            </div>
        </a>

        <!-- Petugas -->
        <a href="users.php" class="card-hover bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="users" class="w-6 h-6 text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800 stat-num"><?= $totalOfficer; ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Total Petugas</p>
            </div>
        </a>

        <!-- Pengguna -->
        <div class="card-hover bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="user" class="w-6 h-6 text-purple-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800 stat-num"><?= $totalUser; ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Pengguna</p>
            </div>
        </div>

        <!-- Transaksi -->
        <a href="reports.php?tab=transaction" class="card-hover bg-[#0B483A] rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="receipt" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-white stat-num"><?= $totalOrder; ?></p>
                <p class="text-xs text-white/60 mt-0.5">Total Transaksi</p>
            </div>
        </a>
    </div>

    <!-- REVENUE BANNER -->
    <div class="max-w-6xl mx-auto px-6 mt-4">
        <div class="bg-gradient-to-r from-[#199276] to-[#0B483A] rounded-2xl px-8 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <p class="text-white/70 text-xs">Total Pendapatan (Transaksi Diterima)</p>
                    <p class="text-white text-2xl font-bold mt-0.5">Rp <?= number_format($totalRevenue, 0, ',', '.'); ?></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-white/60 text-xs">Pending</p>
                <p class="text-yellow-300 font-bold text-lg"><?= $totalPending; ?> pesanan</p>
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS -->
    <div class="max-w-6xl mx-auto px-6 mt-6 mb-10">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#0B483A]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4 text-[#0B483A]"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Transaksi Terbaru</h3>
                </div>
                <a href="reports.php?tab=transaction" class="text-sm text-[#199276] font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Pelanggan</th>
                            <th class="py-3 px-6 text-left">Total</th>
                            <th class="py-3 px-6 text-center">Metode</th>
                            <th class="py-3 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php while($row = mysqli_fetch_assoc($recentResult)): ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6 font-mono text-gray-500 text-xs">#<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="py-3.5 px-6 font-medium text-gray-800"><?= htmlspecialchars($row['customer']); ?></td>
                            <td class="py-3.5 px-6 font-semibold text-[#0B483A]">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium <?= $row['payment_method'] === 'transfer' ? 'bg-blue-50 text-blue-600' : 'bg-yellow-50 text-yellow-700'; ?>">
                                    <?= strtoupper($row['payment_method']); ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium <?= $row['status'] === 'accepted' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600'; ?>">
                                    <?= $row['status'] === 'accepted' ? 'Diterima' : 'Menunggu'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
