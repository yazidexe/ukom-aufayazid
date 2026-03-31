<?php
include 'auth.php';
require '../Admin/config/database.php';

$officerName = $_SESSION['officer_name'];


$stockData = mysqli_query($conn, "SELECT id, name, category, stock FROM products ORDER BY id DESC");

$salesData = mysqli_query($conn, "
    SELECT 
        sales.id,
        sales.customer_name,
        sales.total_price,
        sales.created_at,
        IFNULL(SUM(transactions.quantity),0) AS total_quantity
    FROM sales
    LEFT JOIN transactions 
        ON sales.id = transactions.sale_id
    GROUP BY sales.id
    ORDER BY sales.id DESC
");

$transactionData = mysqli_query($conn, "
    SELECT 
        transactions.id,
        sales.customer_name,
        products.name AS product_name,
        products.category,
        sales.created_at,
        transactions.quantity,
        transactions.subtotal
    FROM transactions
    JOIN sales ON transactions.sale_id = sales.id
    JOIN products ON transactions.product_id = products.id
    ORDER BY transactions.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

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
                class="hover:text-[#0B483A] transition">
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
                class="text-[#0B483A] font-semibold">
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

    <!-- TABEL CONTENT -->
    
        <!-- TAB NAVIGATION -->
        <div class="px-8 mt-6">

            <div class="flex gap-2">

                <button onclick="showTab('stock')" id="tab-stock"
                    class="tab-btn bg-[#0B483A] text-white px-5 py-2 rounded-t-lg flex items-center gap-2">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                    Stock
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
        <div id="stock" class="tab-content px-8 mt-2">

            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3 text-left">Name</th>
                            <th class="p-3">Category</th>
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
            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg mt-2">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Actions</th>
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
            <div class="overflow-x-auto border border-[#0B483A] rounded-b-lg mt-2">
                <table class="w-full text-sm">

                    <thead class="bg-[#0B483A] text-white">
                        <tr>
                            <th class="p-3">Id</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($transactionData)) : ?>
                        <tr class="border-t text-center">

                            <td class="p-3"><?= $row['id'] ?></td>

                            <td class="p-3">
                                <?= $row['customer_name'] ?>
                            </td>

                            <td class="p-3">
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

                            <td class="p-3">
                                <a href="receipt.php?id=<?= $row['id'] ?>"
                                    class="bg-[#199276] text-white px-3 py-1 rounded">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </a>
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
    </script>

</body>
</html>