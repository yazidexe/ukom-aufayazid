<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$count = 0;

if(isset($_SESSION['cart'])){
    $count = array_sum($_SESSION['cart']); // total qty
}
?>

<!-- NAVBAR -->
<nav class="fixed top-0 left-0 w-full z-50 
bg-[#0B5C4A]/90 backdrop-blur-md 
flex items-center justify-between px-10 py-5">

    <!-- LEFT -->
    <div class="flex items-center gap-6 text-sm">

        <a href="index.php" class="text-white hover:opacity-80 transition">
            Home
        </a>

        <!-- DROPDOWN SHOP (pindah ke sini) -->
        <div class="relative">

            <button id="shopButton"
                class="flex items-center gap-2 text-white hover:opacity-80 transition">
                Shop
                <svg id="arrowIcon" xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 transition-transform duration-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="shopMenu"
                class="absolute left-0 mt-3 w-48
                    bg-[#199276] text-white rounded-md shadow-lg
                    opacity-0 translate-y-2 scale-95
                    pointer-events-none
                    transition-all duration-200 ease-out">

                <a href="category.php?kategori=Shirt"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Shirt
                </a>

                <a href="category.php?kategori=Tshirt"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    T-Shirt
                </a>

                <a href="category.php?kategori=Pants"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Pants
                </a>

                <a href="category.php?kategori=Shoes"
                class="block px-5 py-3 hover:bg-white/20 transition">
                    Shoes
                </a>

            </div>

        </div>

    </div>

    <!-- CENTER BRAND -->
    <div class="brand text-2xl text-white tracking-wide">
        Azula
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-5">

    <a href="cart.php" class="relative">
    
        <!-- ICON -->
        <i data-lucide="shopping-cart" class="w-6 h-6"></i>

        <!-- BADGE -->
        <?php if($count > 0): ?>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs 
                w-5 h-5 flex items-center justify-center rounded-full
                animate-bounce">
            <?= $count; ?>
        </span>
        <?php endif; ?>

    </a>

    <?php if(isset($_SESSION['user_id'])): ?>

        <!-- USER LOGIN -->
        <span class="text-sm text-white">
            Hi, <?= $_SESSION['user_name']; ?>
        </span>

        <a href="logout.php" class="text-white hover:text-red-400 transition">
            <i data-lucide="log-out"></i>
        </a>

    <?php else: ?>

        <!-- BELUM LOGIN -->
        <a href="register.php" class="text-white hover:opacity-80 transition">
            <i data-lucide="log-in"></i>
        </a>

    <?php endif; ?>

</div>
</nav>

<script>
    const shopButton = document.getElementById("shopButton");
    const shopMenu = document.getElementById("shopMenu");
    const arrowIcon = document.getElementById("arrowIcon");

    let isOpen = false;

    shopButton.addEventListener("click", function (e) {
        e.stopPropagation();
        isOpen = !isOpen;

        if (isOpen) {
            shopMenu.classList.remove("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.add("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.add("rotate-180");
        } else {
            shopMenu.classList.add("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.remove("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.remove("rotate-180");
        }
    });

    document.addEventListener("click", function (e) {
        if (!shopMenu.contains(e.target) && !shopButton.contains(e.target)) {
            shopMenu.classList.add("opacity-0", "translate-y-2", "scale-95", "pointer-events-none");
            shopMenu.classList.remove("opacity-100", "translate-y-0", "scale-100");
            arrowIcon.classList.remove("rotate-180");
            isOpen = false;
        }
    });
</script>