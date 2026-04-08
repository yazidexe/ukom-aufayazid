<?php
session_start();
if(isset($_SESSION['officer_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Officer Login</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex">

<!-- LEFT SIDE -->
<div class="w-1/2 bg-white relative hidden md:block">
    <div class="absolute bottom-12 left-12">
        <h1 class="text-[70px] font-bold leading-tight">
            Halaman <br> Masuk<br>
            untuk <br><span class="text-[#0B483A]">Petugas</span>
        </h1>
    </div>
</div>

<!-- RIGHT SIDE -->
<div class="w-full md:w-1/2 bg-[#0B483A] flex items-center justify-center">
    <form action="login_process.php" method="POST" class="w-full max-w-sm px-8">

        <!-- Title -->
        <h2 class="text-center text-5xl font-bold text-[#199276] mb-10">
            MASUK
        </h2>

        <!-- Email -->
        <div class="mb-6">
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

        <!-- Password -->
        <div class="mb-8 relative">
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

        <!-- Button -->
        <button
            type="submit"
            class="w-full py-6 rounded-full bg-[#199276] text-[#0B483A] text-2xl
                font-bold
                transition-all duration-300 ease-out
                hover:bg-[#1fa084]
                hover:-translate-y-1
                hover:shadow-xl
                active:translate-y-0
                active:shadow-md"
        >
            Masuk
        </button>

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