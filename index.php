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
</style>
</head>

<body class="bg-white text-white">

<?php include "includes/navbar.php"; ?>

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

<script>
    lucide.createIcons();

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

</script>

</html>
</body>