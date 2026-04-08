<?php
include 'auth.php';
require '../Admin/config/database.php';

$productResult    = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProduct     = mysqli_fetch_assoc($productResult)['total'] ?? 0;

$orderResult      = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$totalOrder       = mysqli_fetch_assoc($orderResult)['total'] ?? 0;

$pendingResult    = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='pending'");
$totalPending     = mysqli_fetch_assoc($pendingResult)['total'] ?? 0;

$acceptedResult   = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='accepted'");
$totalAccepted    = mysqli_fetch_assoc($acceptedResult)['total'] ?? 0;

// 5 transaksi terbaru yang perlu diproses (pending dulu)
$recentResult = mysqli_query($conn, "
    SELECT orders.id, users.name AS customer, orders.total, orders.payment_method, orders.status, orders.created_at, orders.expedition_name, orders.shipping_type
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY FIELD(orders.status, 'pending', 'accepted'), orders.id DESC LIMIT 6
");

$officerName = $_SESSION['officer_name'] ?? 'Petugas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Petugas - Azula</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .card-hover { transition: all 0.25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(11,72,58,0.12); }
        @keyframes pulse-badge {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.6; }
        }
        .badge-pulse { animation: pulse-badge 2s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- NAVBAR -->
    <div class="bg-[#0B483A] rounded-b-3xl px-8 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-[#199276] rounded-xl flex items-center justify-center">
                <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-white text-lg font-bold leading-tight">Panel Petugas</h1>
                <p class="text-[#199276] text-xs">Halo, <span class="font-semibold"><?= htmlspecialchars($officerName); ?></span>!</p>
            </div>
        </div>
        <a href="logout.php" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-xl font-medium transition text-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
        </a>
    </div>

    <!-- MENU -->
    <div class="px-8 mt-4 flex justify-center">
        <ul class="flex gap-8 text-sm text-gray-400">
            <li><a href="dashboard.php" class="text-[#0B483A] font-semibold">beranda</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div class="max-w-5xl mx-auto px-6 mt-8 space-y-6">

        <!-- GREETING + ALERT ROW -->
        <div class="bg-[#0B483A] rounded-2xl px-8 py-6 flex items-center justify-between relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="80%" cy="50%" r="120" fill="white"/>
                    <circle cx="10%" cy="80%" r="80" fill="white"/>
                </svg>
            </div>
            <div class="relative">
                <p class="text-[#199276] text-sm font-medium">Selamat bertugas 🚀</p>
                <h2 class="text-white text-2xl font-bold mt-1"><?= htmlspecialchars($officerName); ?></h2>
                <p class="text-white/50 text-sm mt-1"><?= date('d F Y'); ?> · <?= date('H:i'); ?> WIB</p>
            </div>
            <div class="relative flex items-center gap-3">
                <?php if($totalPending > 0): ?>
                <div class="badge-pulse bg-yellow-400 text-yellow-900 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-bold">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <?= $totalPending; ?> Pesanan Menunggu
                </div>
                <?php else: ?>
                <div class="bg-green-500/20 text-green-300 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Semua Selesai!
                </div>
                <?php endif; ?>
                <a href="transactions.php" class="bg-[#199276] text-white px-4 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 hover:bg-[#148066] transition">
                    Proses Pesanan <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="products.php" class="card-hover bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-[#0B483A]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="package" class="w-6 h-6 text-[#0B483A]"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalProduct; ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Total Produk</p>
                </div>
            </a>

            <a href="transactions.php" class="card-hover bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-indigo-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalOrder; ?></p>
                    <p class="text-xs text-gray-500 mt-0.5">Total Pesanan</p>
                </div>
            </a>

            <a href="transactions.php" class="card-hover bg-yellow-400 rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-white/30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-900"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-900"><?= $totalPending; ?></p>
                    <p class="text-xs text-yellow-800 mt-0.5">Pending</p>
                </div>
            </a>

            <div class="card-hover bg-[#0B483A] rounded-2xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white"><?= $totalAccepted; ?></p>
                    <p class="text-xs text-white/60 mt-0.5">Diterima</p>
                </div>
            </div>
        </div>

        <!-- DAFTAR PESANAN PERLU DIPROSES -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#0B483A]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="list-checks" class="w-4 h-4 text-[#0B483A]"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Pesanan Terkini</h3>
                </div>
                <a href="transactions.php" class="text-sm text-[#199276] font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6 text-left">ID</th>
                            <th class="py-3 px-6 text-left">Pelanggan</th>
                            <th class="py-3 px-6 text-left">Ekspedisi</th>
                            <th class="py-3 px-6 text-left">Total</th>
                            <th class="py-3 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php while($row = mysqli_fetch_assoc($recentResult)): ?>
                        <tr class="hover:bg-gray-50/50 transition <?= $row['status'] === 'pending' ? 'bg-yellow-50/30' : ''; ?>">
                            <td class="py-3.5 px-6 font-mono text-gray-500 text-xs">#<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td class="py-3.5 px-6 font-medium text-gray-800"><?= htmlspecialchars($row['customer']); ?></td>
                            <td class="py-3.5 px-6 text-gray-600 text-xs">
                                <?= $row['expedition_name'] ? $row['expedition_name'] . ' · ' . $row['shipping_type'] : '-'; ?>
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-[#0B483A]">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
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

        <div class="pb-8"></div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>