<?php
include '../Admin/config/database.php';

$query = "SELECT 
            t.id,
            p.name AS product_name,
            s.customer_name,
            p.category,
            t.quantity,
            t.subtotal,
            t.proof_of_payment,
            t.created_at
          FROM transactions t
          JOIN products p ON t.product_id = p.id
          JOIN sales s ON t.sale_id = s.id
          ORDER BY t.id DESC";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

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
        <h1 class="text-white text-2xl font-semibold">
            Dashboard Admin
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
                <a href="users.php"
                class="hover:text-[#0B483A] transition">
                    user management
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
                class="text-[#0B483A] font-semibold">
                    transaction management
                </a>
            </li>
            <li>
                <a href="backup.php"
                class="hover:text-[#0B483A] transition">
                    data backup/restore
                </a>
            </li>
        </ul>
    </div>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['product_name']; ?></td>
        <td><?= $row['customer_name']; ?></td>
        <td><?= $row['category']; ?></td>
        <td><?= $row['quantity']; ?></td>
        <td>Rp<?= number_format($row['total_price'], 0, ',', '.'); ?></td>
        <td>
            <button onclick="openProofModal('<?= $row['proof_of_payment']; ?>')">
                View
            </button>
        </td>
        <td>
            <a href="receipt.php?id=<?= $row['id']; ?>">
                <button>Receipt</button>
            </a>
        </td>
    </tr>
    <?php } ?>

    <!-- MODAL POP -->
    <div id="proofModal" class="modal">
        <span onclick="closeModal()">X</span>
        <img id="proofImage" style="width:100%">
    </div>

    <script>
    function openProofModal(imageName) {
        document.getElementById("proofImage").src = "uploads/" + imageName;
        document.getElementById("proofModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("proofModal").style.display = "none";
    }
    </script>


    <script>
        lucide.createIcons();
    </script>

</body>
</html>