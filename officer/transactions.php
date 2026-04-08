<?php
include '../Admin/config/database.php';

$query = "
    SELECT 
        order_items.id,
        products.name AS product_name,
        users.name AS customer_name,
        users.email AS customer_email,
        products.category,
        order_items.qty AS quantity,
        (order_items.qty * order_items.price) AS subtotal,
        orders.proof,
        orders.address,
        orders.payment_method,
        orders.created_at,
        orders.expedition_name,
        orders.shipping_type,
        orders.shipping_cost
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    JOIN orders ON order_items.order_id = orders.id
    JOIN users ON orders.user_id = users.id
    ORDER BY order_items.id DESC
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

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
                <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-white text-lg font-bold leading-tight">Panel Petugas</h1>
                <p class="text-[#199276] text-xs">Halo, <span class="font-semibold"><?php @session_start(); echo htmlspecialchars($_SESSION['officer_name'] ?? 'Petugas'); ?></span>!</p>
            </div>
        </div>
        <a href="logout.php" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-xl font-medium transition text-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
        </a>
    </div>

    <!-- MENU -->
    <div class="px-8 mt-4 flex justify-center">
        <ul class="flex gap-8 text-sm text-gray-400">
            <li><a href="dashboard.php" class="hover:text-[#0B483A] transition">beranda</a></li>
            <li><a href="products.php" class="hover:text-[#0B483A] transition">manajemen produk</a></li>
            <li><a href="reports.php" class="hover:text-[#0B483A] transition">laporan</a></li>
            <li><a href="transactions.php" class="text-[#0B483A] font-semibold transition">manajemen transaksi</a></li>
        </ul>
    </div>

    <!-- TABLE -->
    <div class="px-8 mt-6">

    <div class="overflow-x-auto border border-[#0B483A] rounded-lg">
    <table class="w-full text-sm">

        <thead class="bg-[#0B483A] text-white">
            <tr>
                <th class="p-3">Product</th>
                <th class="p-3">Pelanggan</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Qty</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Pengiriman</th>
                <th class="p-3">POP</th>
                <th class="p-3">Alamat</th>
                <th class="p-3">Payment</th>
                <th class="p-3">Receipt</th>
            </tr>
        </thead>

        <tbody>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="border-t text-start">

                <td class="p-3"><?= $row['product_name']; ?></td>

                <td class="p-3">
                    <div class="text-sm font-medium"><?= $row['customer_name']; ?></div>
                    <div class="text-xs text-gray-400"><?= $row['customer_email']; ?></div>
                </td>

                <td class="p-3 text-center"><?= $row['category']; ?></td>

                <td class="p-3 text-center"><?= $row['quantity']; ?></td>

                <td class="p-3 font-semibold text-[#0B483A]">
                    Rp<?= number_format($row['subtotal'], 0, ',', '.'); ?>
                </td>

                <td class="p-3 text-sm">
                    <?php if($row['expedition_name']): ?>
                        <div class="font-bold text-[#0B483A]"><?= $row['expedition_name']; ?></div>
                        <div class="text-xs text-gray-500"><?= $row['shipping_type']; ?></div>
                    <?php else: ?>
                        <span class="text-gray-400">-</span>
                    <?php endif; ?>
                </td>

                <td class="p-3 text-center">
                    <?php if($row['proof']): ?>
                        <button onclick="openProofModal('<?= $row['proof']; ?>')"
                            class="bg-blue-500 text-white px-3 py-1 rounded">
                            View
                        </button>
                    <?php else: ?>
                        <span class="text-gray-400">-</span>
                    <?php endif; ?>
                </td>

                <td class="p-3"><?= $row['address']; ?></td>

                    <td class="p-3 text-center">
                        <?php if($row['payment_method'] == 'transfer'): ?>
                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">
                                Transfer
                            </span>
                        <?php else: ?>
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">
                                COD
                            </span>
                        <?php endif; ?>
                    </td>   

                <td class="p-3">
                    <a href="receipt.php?id=<?= $row['id']; ?>">
                        <button class="bg-[#199276] text-white px-3 py-1 rounded">
                            Receipt
                        </button>
                    </a>
                </td>

            </tr>
        <?php } ?>

        </tbody>
    </table>
    </div>

    </div>

    <!-- MODAL -->
    <div id="proofModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        
        <div class="bg-white p-4 rounded-xl relative max-w-lg w-full">
            
            <button onclick="closeModal()" 
                class="absolute top-2 right-3 text-gray-500 text-xl">
                ✕
            </button>

            <img id="proofImage" class="w-full rounded-lg">

        </div>
    </div>

    <script>
    lucide.createIcons();
    
    function openProofModal(imageName) {
    document.getElementById("proofImage").src = "../uploads/proofs/" + imageName;
    document.getElementById("proofModal").classList.remove("hidden");
    document.getElementById("proofModal").classList.add("flex");
    }

    function closeModal() {
        document.getElementById("proofModal").classList.add("hidden");
    }
    </script>

</body>
</html>