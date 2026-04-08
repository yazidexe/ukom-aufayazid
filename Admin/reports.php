<?php
include '../Admin/config/database.php';

$stockData = mysqli_query($conn, "SELECT id, name, category, stock FROM products ORDER BY id DESC");

$salesData = mysqli_query($conn, "
    SELECT 
        orders.id,
        users.name AS customer_name,
        orders.total AS total_price,
        orders.created_at,
        SUM(order_items.qty) AS total_quantity
    FROM orders
    JOIN users ON orders.user_id = users.id
    JOIN order_items ON orders.id = order_items.order_id
    GROUP BY orders.id
    ORDER BY orders.id DESC
");

$transactionData = mysqli_query($conn, "
    SELECT 
        order_items.id,
        orders.id AS order_id,
        users.name AS customer_name,
        products.name AS product_name,
        products.category,
        orders.created_at,
        order_items.qty AS quantity,
        (order_items.qty * order_items.price) AS subtotal,
        orders.proof,
        orders.status
    FROM order_items
    JOIN orders ON order_items.order_id = orders.id
    JOIN users ON orders.user_id = users.id
    JOIN products ON order_items.product_id = products.id
    ORDER BY order_items.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dasbor Admin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

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
            <li><a href="dashboard.php" class="hover:text-[#0B483A] transition">beranda</a></li>
            <li><a href="users.php" class="hover:text-[#0B483A] transition">manajemen pengguna</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="text-[#0B483A] font-semibold transition">laporan</a></li>
            <li><a href="transactions.php" class="hover:text-[#0B483A] transition">manajemen transaksi</a></li>
            <li><a href="backup.php" class="hover:text-[#0B483A] transition">backup data</a></li>
        </ul>
    </div>

    <!-- TABEL CONTENT -->
    
        <!-- TAB NAVIGATION -->
        <div class="px-8 mt-6">

            <div class="flex">

                <button onclick="showTab('stock')" id="tab-stock"
                    class="tab-btn bg-[#0B483A] text-white px-5 py-2 rounded-t-lg flex items-center gap-2">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                    Stok
                </button>

                <button onclick="showTab('sales')" id="tab-sales"
                    class="tab-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-t-lg flex items-center gap-2">
                    <i data-lucide="badge-dollar-sign" class="w-4 h-4"></i>
                    Sales
                </button>

                <button onclick="showTab('transaction')" id="tab-transaction"
                    class="tab-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-t-lg flex items-center gap-2">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                    Transaction
                </button>

            </div>

        </div>

        <!-- STOCK TABLE -->
        <div id="stock" class="tab-content px-8">

            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Quantity</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($stockData)) : ?>
                        <tr class="border-t text-center">

                            <td class="p-3"><?= $row['id'] ?></td>

                            <td class="p-3 text-left">
                                <?= $row['name'] ?>
                            </td>

                            <td class="p-3">
                                <?= $row['category'] ?>
                            </td>

                            <td class="p-3 text-[#0B483A] font-semibold">
                                <?= $row['stock'] ?>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>

        </div>

        <!-- SALES TABLE -->
        <div id="sales" class="tab-content px-8 hidden">
            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($salesData)) : ?>
                        <tr class="border-t text-center">

                            <td class="p-3"><?= $row['id'] ?></td>

                            <td class="p-3">
                                <?= $row['customer_name'] ?>
                            </td>

                            <td class="p-3">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>

                            <td class="p-3">
                                <?= $row['total_quantity'] ?>
                            </td>

                            <td class="p-3 font-semibold text-[#0B483A]">
                                Rp<?= number_format($row['total_price'],0,',','.') ?>
                            </td>

                            <td class="p-3">
                                <a href="receipt.php?id=<?= $row['id'] ?>"
                                    class="bg-[#199276] text-white px-3 py-1 rounded">
                                    See Receipt
                                </a>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- TRANSACTION TABLE -->
        <div id="transaction" class="tab-content px-8 hidden">
            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Bukti Transfer (POP)</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($transactionData)) : ?>
                        <tr class="border-t text-center">

                            <td class="p-3"><?= $row['id'] ?></td>

                            <td class="p-3 text-start">
                                <?= $row['customer_name'] ?>
                            </td>

                            <td class="p-3 text-start">
                                <?= $row['product_name'] ?>
                            </td>

                            <td class="p-3">
                                <?= $row['category'] ?>
                            </td>

                            <td class="p-3">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>

                            <td class="p-3">
                                <?= $row['quantity'] ?>
                            </td>

                            <td class="p-3 font-semibold text-[#0B483A]">
                                Rp<?= number_format($row['subtotal'],0,',','.') ?>
                            </td>

                            <td class="p-3 text-center">
                                <?php if(!empty($row['proof'])): ?>
                                    <button onclick="openProofModal('<?= $row['proof']; ?>')"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                                        Lihat Bukti
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">COD / Tidak ada</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-3">
                                <?php if($row['status'] == 'accepted'): ?>
                                    <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">
                                        Accepted
                                    </span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="p-3 flex flex-col gap-2 items-center">

                                <!-- STRUK -->
                                <a href="receipt.php?id=<?= $row['id']; ?>">
                                    <button class="bg-[#199276] text-white px-4 py-1 rounded text-xs">
                                        Receipt
                                    </button>
                                </a>

                                <!-- ACCEPT -->
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="accept_order.php?id=<?= $row['id']; ?>">
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                            Accept
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <button class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-xs cursor-not-allowed">
                                        Accepted
                                    </button>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>

    <script>
        lucide.createIcons();

        function showTab(tabName) {

            // hide semua content
            document.querySelectorAll(".tab-content").forEach(el => {
                el.classList.add("hidden");
            });

            // reset semua tombol
            document.querySelectorAll(".tab-btn").forEach(btn => {
                btn.classList.remove("bg-[#0B483A]", "text-white");
                btn.classList.add("bg-gray-200", "text-gray-700");
            });

            // tampilkan tab dipilih
            document.getElementById(tabName).classList.remove("hidden");

            // aktifkan tombolnya
            const activeBtn = document.getElementById("tab-" + tabName);
            activeBtn.classList.remove("bg-gray-200", "text-gray-700");
            activeBtn.classList.add("bg-[#0B483A]", "text-white");
        }

        function openProofModal(imageName) {
            const modal = document.getElementById("proofModal");
            const box = document.getElementById("proofModalBox");
            document.getElementById("proofImage").src = "../uploads/proofs/" + imageName;
            
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            // Animasi masuk
            setTimeout(() => {
                modal.classList.add("opacity-100");
                box.classList.remove("scale-90", "opacity-0");
                box.classList.add("scale-100", "opacity-100");
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById("proofModal");
            const box = document.getElementById("proofModalBox");

            // Animasi keluar
            modal.classList.remove("opacity-100");
            box.classList.remove("scale-100", "opacity-100");
            box.classList.add("scale-90", "opacity-0");

            setTimeout(() => {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }, 300);
        }
    </script>

    <!-- MODAL BUKTI TRANSFER (POP) -->
    <div id="proofModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm opacity-0 transition-opacity duration-300"
        onclick="closeModal()">

        <div id="proofModalBox"
            class="bg-white rounded-2xl shadow-2xl relative max-w-lg w-full mx-4 scale-90 opacity-0 transition-all duration-300"
            onclick="event.stopPropagation()">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="image" class="w-4 h-4 text-blue-500"></i>
                    </div>
                    <p class="font-semibold text-gray-800 text-sm">Bukti Transfer Pembayaran</p>
                </div>
                <button onclick="closeModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-400 transition-all duration-200 font-bold text-base">
                    ✕
                </button>
            </div>

            <!-- IMAGE -->
            <div class="p-4">
                <img id="proofImage" src="" class="w-full rounded-xl border border-gray-100 object-contain max-h-[500px]" alt="Bukti Transfer">
            </div>

        </div>
    </div>

</body>
</html>