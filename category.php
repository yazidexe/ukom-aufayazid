<?php
include "Admin/config/database.php";

// Ambil kategori dari URL
$category = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Kalau kosong redirect (biar aman)
if ($category == '') {
    header("Location: index.php");
    exit;
}

// Query berdasarkan kategori
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE category = ? ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($category); ?> - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
.brand { font-family: 'Playfair Display', serif; }
</style>
</head>

<body class="bg-white text-black">

<?php include "includes/navbar.php"; ?>

<section class="px-10 py-12 max-w-[1250px] mx-auto">
    
    <h1 class="text-3xl font-semibold text-[#0B5C4A] mt-20 mb-10 capitalize">
        <?= htmlspecialchars($category); ?>
    </h1>

    <div class="grid grid-cols-3 gap-10">
        
        <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($product = mysqli_fetch_assoc($result)) : ?>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                
                <!-- Image -->
                <div class="h-64 overflow-hidden cursor-pointer"
                    data-name="<?= htmlspecialchars($product['name'], ENT_QUOTES); ?>"
                    data-price="<?= $product['price']; ?>"
                    data-picture="<?= htmlspecialchars($product['picture'], ENT_QUOTES); ?>"
                    data-description="<?= htmlspecialchars($product['description'], ENT_QUOTES); ?>"
                    data-stock="<?= $product['stock']; ?>"
                    onclick="openModal(this)">
                    
                    <img src="admin/uploads/products/<?= $product['picture']; ?>" 
                        class="w-full h-full object-cover">
                </div>

                <!-- Content -->
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <?= htmlspecialchars($product['name']); ?>
                    </h2>

                    <p class="text-sm text-gray-500 mb-2">
                        <?= htmlspecialchars($product['category']); ?>
                    </p>

                    <p class="text-md font-semibold text-[#0B5C4A] mb-4">
                        Rp <?= number_format($product['price'],0,',','.'); ?>
                    </p>

                    <button class="w-full py-2 bg-[#0B5C4A] text-white rounded-lg hover:opacity-90 transition">
                        Add to Cart
                    </button>
                </div>

            </div>

        <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-500">Belum ada produk di kategori ini.</p>
        <?php endif; ?>

    </div>

</section>

<!-- MODAL -->
<section>
    <div id="productModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white w-[900px] max-h-[90vh] overflow-y-auto rounded-lg p-8 relative flex gap-8">

            <button onclick="closeModal()" 
                    class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">
                ✕
            </button>

            <div class="w-1/2">
                <img id="modalImage" class="w-full h-[400px] object-cover rounded-xl">
                
                <h2 id="modalName" class="text-2xl text-gray-800 font-semibold mt-4"></h2>
                <p id="modalPrice" class="text-2xl font-bold text-[#0B5C4A] mt-2"></p>
            </div>

            <div class="w-1/2">
                <p id="modalDescription" class="text-gray-600 mb-6 whitespace-pre-line"></p>
                <p id="modalStock" class="text-sm text-gray-500 mb-8"></p>

                <button class="w-full py-3 mb-4 bg-[#0B5C4A] text-white rounded-lg hover:opacity-90 transition">
                    Add to Cart
                </button>
            </div>

        </div>
    </div>
</section>

<script>
lucide.createIcons();

function openModal(element) {

    const name = element.dataset.name;
    const price = element.dataset.price;
    const picture = element.dataset.picture;
    const description = element.dataset.description;
    const stock = element.dataset.stock;

    document.getElementById('modalName').innerText = name;
    document.getElementById('modalPrice').innerText =
        "Rp " + new Intl.NumberFormat('id-ID').format(price);

    document.getElementById('modalImage').src =
        "admin/uploads/products/" + picture;

    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalStock').innerText = "Stock: " + stock;

    document.getElementById('productModal').classList.remove('hidden');
    document.getElementById('productModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
}
</script>

</body>
</html>