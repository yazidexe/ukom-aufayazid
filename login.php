<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - Azula</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script src="https://unpkg.com/lucide@latest"></script>

<style>
body { font-family: 'Poppins', sans-serif; }
</style>
</head>

<body class="min-h-screen flex">

<!-- LEFT SIDE (IMAGE) -->
<div class="w-1/2 bg-white hidden md:flex items-center justify-center relative">
    
    <img src="assets/login.png" 
        class="w-[80%] object-contain">

</div>

<!-- RIGHT SIDE -->
<div class="w-full md:w-1/2 bg-[#0B5C4A] flex items-center justify-center">

<form action="process_login.php" method="POST" class="w-full max-w-sm px-8">

    <!-- TITLE -->
    <h2 class="text-center text-5xl font-bold text-[#199276] mb-10">
        MASUK
    </h2>

    <!-- EMAIL -->
    <div class="mb-2">
        <input
            type="email"
            name="email"
            required
            placeholder="email"
            class="w-full px-8 py-5 bg-transparent border border-[#199276] rounded-full
                   placeholder:italic placeholder:text-[#199276]
                   text-[#199276] placeholder:text-sm focus:outline-none"
        >
    </div>

    <!-- PASSWORD -->
    <div class="mb-6 relative">
        <input
            type="password"
            name="password"
            id="password"
            required
            placeholder="kata sandi"
            class="w-full px-8 py-5 bg-transparent border border-[#199276] rounded-full
                   placeholder:italic placeholder:text-[#199276]
                   text-[#199276] placeholder:text-sm focus:outline-none"
        >

        <button type="button" onclick="togglePassword()"
            class="absolute right-5 top-1/2 -translate-y-1/2 text-[#199276]">
            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- LOGIN BUTTON -->
    <button
        type="submit"
        class="w-full py-6 rounded-full bg-[#199276] text-[#0B483A] text-2xl font-bold
        transition-all duration-300 ease-out
        hover:bg-[#1fa084]
        hover:-translate-y-1
        hover:shadow-xl"
    >
        Masuk
    </button>

    <!-- REGISTER LINK -->
    <p class="text-center text-sm font-thin text-white mt-6">
        Belum punya akun?
        <a href="register.php" class="text-[#199276] font-bold hover:underline">
            Daftar
        </a>
    </p>

    <!-- BACK -->
    <a href="index.php"
        class="block text-center text-xs text-white/20 mt-3 hover:text-white/50 ">
        ← Kembali
    </a>

</form>

</div>

<script>
lucide.createIcons();

function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}
</script>

</body>
</html>