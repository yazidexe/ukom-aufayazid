<?php
session_start();
include "Admin/config/database.php";

$cart = $_SESSION['cart'] ?? [];

$products = [];

if(!empty($cart)){
    $ids = implode(',', array_keys($cart));
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
    
    while($row = mysqli_fetch_assoc($query)){
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cart - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
.brand { font-family: 'Playfair Display', serif; }
</style>
</head>

<body class="bg-gray-100">

<?php include "includes/cart_header.php"; ?>

<!-- FORM UTAMA -->
<form action="checkout.php" method="POST">

<div class="max-w-[900px] mx-auto mt-10 space-y-6">

<?php if(empty($products)): ?>
    <p class="text-center text-gray-500">Nothing has been added to the cart yet</p>
<?php endif; ?>

<?php foreach($products as $product): 
    $qty = $cart[$product['id']];
?>

<div class="bg-white p-5 rounded-xl shadow flex items-center gap-6">

    <!-- IMAGE -->
    <img src="admin/uploads/products/<?= $product['picture']; ?>" 
         class="w-20 h-20 object-cover rounded-lg">

    <!-- INFO -->
    <div class="flex-1">
        <h2 class="font-semibold text-gray-800">
            <?= htmlspecialchars($product['name']); ?>
        </h2>
        <p class="text-sm text-gray-400">
            <?= htmlspecialchars($product['category']); ?>
        </p>
    </div>

    <!-- QTY -->
    <div class="flex items-center border rounded-full px-3 py-1 gap-3">

        <a href="update_cart.php?id=<?= $product['id']; ?>&action=minus">-</a>

        <span><?= $cart[$product['id']]; ?></span>

        <a href="update_cart.php?id=<?= $product['id']; ?>&action=plus">+</a>

    </div>

    <!-- DELETE -->
    <a href="remove_cart.php?id=<?= $product['id']; ?>" class="text-red-500">
        <i data-lucide="trash-2" class="w-5 h-5"></i>
    </a>

    <!-- CHECKBOX -->
    <input type="checkbox" 
           name="selected[]" 
           value="<?= $product['id']; ?>" 
           class="w-5 h-5">

</div>

<?php endforeach; ?>

</div>

<!-- BUTTON -->
<div class="max-w-[900px] mx-auto mt-10 flex justify-end">
    <button type="submit" class="bg-[#199276] text-[#0B483A] px-14 py-3 rounded-sm hover:bg-[#167B63]">
        Choose
    </button>
</div>

</form>

<script>
lucide.createIcons();
</script>

</body>
</html>