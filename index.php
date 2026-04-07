<?php
session_start();
include "Admin/config/database.php";
$query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
.brand { font-family: 'Playfair Display', serif; }

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.7) translateY(30px);
    }
    50% {
        opacity: 1;
        transform: scale(1.05) translateY(-5px);
    }
    70% {
        transform: scale(0.97) translateY(2px);
    }
    100% {
        transform: scale(1) translateY(0);
    }
}

.animate-bounceIn {
    animation: bounceIn 0.4s ease;
}

</style>
</head>

<body class="bg-white text-white">

<?php include "includes/navbar.php"; ?>

<?php if(isset($_SESSION['success'])): ?>
<div id="toast" class="fixed top-5 right-5 bg-[#199276] text-white px-6 py-3 rounded-lg shadow-lg z-50 opacity-0 translate-y-[-20px] transition-all duration-500">
    <?= $_SESSION['success']; ?>
</div>

<script>
setTimeout(() => {
    const toast = document.getElementById('toast');
    if(toast){
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }
}, 2000);
</script>

<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- HERO PANEL -->
<section class="flex items-center justify-between px-20 bg-[#0B5C4A]">

    <!-- LEFT TEXT -->
    <div class="max-w-xl">
        <h1 class="text-7xl font-semibold leading-tight">
            <span class="text-[#199276] font-medium">Imposible is</span><br>
            <span class="font-bold text-white">Nothing</span>
        </h1>

        <button class="mt-8 px-28 py-3 border border-white rounded-full
        transition-all duration-300
        hover:bg-white hover:text-[#0B5C4A]
        hover:scale-105 active:scale-95">
            Explore More
        </button>

    </div>

    <!-- RIGHT IMAGE -->
    <div>
        <img src="assets/hero-ecm.png" class="w-[650px] h-[650px] object-contain">
    </div>

</section>

<!-- CAROUSEL -->
<section class="mt-16 px-6 py-12"> 
    <h1 class="text-center text-3xl font-medium text-gray-900 mb-16">
        What's New?
    </h1>

    <div class="max-w-[1250px] mx-auto overflow-hidden rounded-2xl relative">

        <div id="carousel" class="flex transition-transform duration-700 ease-in-out">

            <div class="min-w-full">
                <img src="assets/dummy.png" class="w-[1250px] h-[400px] object-cover" />
            </div>

            <div class="min-w-full">
                <img src="assets/dummy2.png" class="w-[1250px] h-[400px] object-cover" />
            </div>

            <div class="min-w-full">
                <img src="assets/dummy3.png" class="w-[1250px] h-[400px] object-cover" />
            </div>

        </div>

    </div>
</section>

<!-- PRODUCT SECTION -->
<section class="px-10 py-12 max-w-[1250px] mx-auto">
    
    <h1 class="text-3xl font-semibold text-[#0B5C4A] mt-10 mb-10 text-center">
        Our Products
    </h1>

    <div class="grid grid-cols-3 gap-10">
        
        <?php if(mysqli_num_rows($query) > 0): ?>
        <?php while($product = mysqli_fetch_assoc($query)) : ?>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                
                <!-- Image -->
                <div class="h-64 overflow-hidden">
                    <img src="admin/uploads/products/<?= $product['picture']; ?>" 
                        class="w-full h-full object-cover cursor-pointer"
                        onclick="openModal(this)"
                        data-id="<?= $product['id']; ?>"
                        data-category="<?= htmlspecialchars($product['category'], ENT_QUOTES); ?>"
                        data-name="<?= htmlspecialchars($product['name'], ENT_QUOTES); ?>"
                        data-price="<?= $product['price']; ?>"
                        data-picture="<?= htmlspecialchars($product['picture'], ENT_QUOTES); ?>"
                        data-description="<?= htmlspecialchars($product['description'], ENT_QUOTES); ?>"
                        data-stock="<?= $product['stock']; ?>">
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

                    <form id="cartForm<?= $product['id']; ?>" action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">

                        <button type="button"
                            onclick="event.stopPropagation(); handleAddToCart(<?= $product['id']; ?>)"
                            class="w-full py-2 bg-[#0B5C4A] text-white rounded-lg hover:opacity-90 transition">
                            Add to Cart
                        </button>
                    </form>
                </div>

    </div>

<?php endwhile; ?>
<?php else: ?>
    <p class="text-gray-500">Belum ada produk.</p>
<?php endif; ?>

</div>
    </div>

</section>
<!-- FOOTER -->
<section>
    <footer class="mt-14">

        <!-- TOP BLOCK -->
        <div class="bg-[#0B5C4A] text-center py-4">
            <h1 class="text-white text-3xl font-semibold brand">
                Azula
            </h1>
            <p class="text-[#199276] text-md mt-2 italic opacity-80">
                your outfit reflects your personality
            </p>
        </div>

        <!-- BOTTOM BLOCK -->
        <div class="bg-black text-center py-3">
            <p class="text-white text-sm">
                © Copyright 2026
            </p>
        </div>

    </footer>
</section>

<!-- MODAL PRODUCT -->
<div id="productModal" 
     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 items-center justify-center">

    <div class="bg-white w-[900px] max-h-[90vh] rounded-xl shadow-xl flex overflow-hidden animate-bounceIn relative">

        <!-- ❌ CLOSE -->
        <button onclick="closeModal()" 
            class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl">
            ✕
        </button>

        <!-- LEFT SIDE -->
        <div class="w-1/2 p-6">

            <div class="relative">
                <img id="modalImage" class="w-full h-[350px] rounded-md object-cover rounded-xl">

                <!-- KATEGORI -->
                <div id="modalCategory"
                    class="absolute bottom-3 right-3 bg-gray-400 text-white px-4 py-1 rounded-br-md text-sm">
                </div>
            </div>

            <!-- PRICE -->
            <p id="modalPrice" class="text-2xl font-bold text-[#0B5C4A] mt-4"></p>

            <!-- STOCK -->
            <p id="modalStock" class="text-sm text-gray-500 mt-1"></p>

        </div>

        <!-- RIGHT SIDE -->
        <div class="w-1/2 p-6 flex flex-col">

            <h2 id="modalName" class="text-3xl font-bold text-[#0B5C4A]"></h2>

            <p id="modalDescription" 
                class="text-gray-500 mt-3 text-sm leading-relaxed flex-1 overflow-y-auto">
            </p>

            <!-- BUTTON -->
            <button id="modalBtn"
                class="mt-6 py-3 rounded-xl bg-[#0B5C4A] text-white hover:opacity-90 transition">
                Add to Cart
            </button>

        </div>

    </div>
</div>

<script>
    lucide.createIcons();

// SESSION
const toast = document.getElementById('toast');

if(toast){
    setTimeout(() => {
        toast.classList.remove('opacity-0','translate-y-[-20px]');
    }, 100);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 2000);
}

//POPUP ALERT

function handleAddToCart(id){

    const isLogin = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

    if(!isLogin){
        showToast("", "assets/alert-login.png");
        return;
    }

    // kalau login → submit form
    document.getElementById('cartForm' + id).submit();
}

function showToast(message, image = null){

    let toast = document.createElement('div');
    toast.className = "fixed inset-0 flex items-center justify-center z-50 bg-black/30 backdrop-blur-sm";

    toast.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl px-2 py-2 text-center animate-bounceIn">
            
            ${image ? `<img src="${image}" class="w-[600px] h-[400px] mx-auto mb-4">` : ''}

            <p class="text-gray-700 font-medium">${message}</p>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 2000);
}

// CAROUSEL
    const carousel = document.getElementById('carousel');
    const slides = carousel.children;
    let index = 0;

    setInterval(() => {
        index++;
        if (index >= slides.length) {
            index = 0;
        }
        carousel.style.transform = `translateX(-${index * 100}%)`;
    }, 2000);

    // MODAL PRODUCT
    function openModal(element){

        const name = element.dataset.name;
        const price = element.dataset.price;
        const picture = element.dataset.picture;
        const description = element.dataset.description;
        const stock = element.dataset.stock;
        const category = element.dataset.category;
        const id = element.closest('[data-id]')?.dataset.id || null;

        document.getElementById('modalName').innerText = name;

        document.getElementById('modalPrice').innerText =
            "Rp " + new Intl.NumberFormat('id-ID').format(price);

        document.getElementById('modalImage').src =
            "admin/uploads/products/" + picture;

        document.getElementById('modalDescription').innerText = description;
        document.getElementById('modalStock').innerText = "Stock: " + stock;
        document.getElementById('modalCategory').innerText = category;

        const btn = document.getElementById('modalBtn');

        const isLogin = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

        if(!isLogin){
            btn.innerText = "Login First";
            btn.classList.add("bg-gray-400","cursor-not-allowed");
            btn.onclick = () => showToast("Login dulu ya!", "assets/alert-login.png");
        } else {
            btn.innerText = "Add to Cart";
            btn.classList.remove("bg-gray-400","cursor-not-allowed");

            btn.onclick = () => {
                // submit form manual
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'add_to_cart.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_id';
                input.value = element.dataset.id;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        document.getElementById('productModal').classList.remove('hidden');
        document.getElementById('productModal').classList.add('flex');
    }

    function closeModal(){
        document.getElementById('productModal').classList.add('hidden');
    }
</script>


</html>
</body>