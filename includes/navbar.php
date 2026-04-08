<?php
$avatar = $_SESSION['avatar'] ?? null;

if(!$avatar && isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $q = mysqli_query($conn, "SELECT avatar FROM users WHERE id='$user_id'");
    $d = mysqli_fetch_assoc($q);
    $avatar = $d['avatar'] ?? null;
}
?>

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
    <div class="flex items-center gap-5 text-white">

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

        <div class="flex items-center gap-4">

            <?php if(isset($_SESSION['user_id'])): ?>

                <!-- USER LOGIN -->
                <a href="profile.php" class="flex items-center gap-2">

                    <span class="text-sm text-white">
                        <?= $_SESSION['user_name']; ?>
                    </span>

                    <img src="<?= !empty($_SESSION['avatar']) 
                        ? '/ukom-project/'.$_SESSION['avatar'] 
                        : 'assets/default-avatar.png'; ?>" 
                    class="w-8 h-8 rounded-full object-cover border-2 border-white">
                    </a>

            <?php else: ?>

                <!-- BELUM LOGIN -->
                <a href="profile.php">
                    <img src="assets/default-avatar.png"
                        class="w-8 h-8 rounded-full object-cover border-2 border-white hover:opacity-80 transition">
                </a>

            <?php endif; ?>

        </div>

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

<script>
const profileBtn = document.getElementById("profileBtn");
const profileMenu = document.getElementById("profileMenu");
const profileArrow = document.getElementById("profileArrow");

let profileOpen = false;

if(profileBtn){
    profileBtn.addEventListener("click", function(e){
        e.stopPropagation();
        profileOpen = !profileOpen;

        if(profileOpen){
            profileMenu.classList.remove("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.add("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.add("rotate-180");
        } else {
            profileMenu.classList.add("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.remove("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.remove("rotate-180");
        }
    });

    document.addEventListener("click", function(e){
        if(!profileMenu.contains(e.target) && !profileBtn.contains(e.target)){
            profileMenu.classList.add("opacity-0","translate-y-2","scale-95","pointer-events-none");
            profileMenu.classList.remove("opacity-100","translate-y-0","scale-100");
            profileArrow.classList.remove("rotate-180");
            profileOpen = false;
        }
    });
}
</script>