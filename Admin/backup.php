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
                class="hover:text-[#0B483A] transition">
                    transaction management
                </a>
            </li>
            <li>
                <a href="backup.php"
                class="text-[#0B483A] font-semibold">
                    data backup/restore
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
        <div class="p-6">

            <!-- BACKUP SECTION -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
                <h2 class="text-2xl font-semibold text-green-800 mb-4 flex items-center gap-2">
                    <i data-lucide="database-backup" class="w-6 h-6"></i> Backup
                    
                    
                </h2>

                <div class="border-t pt-6">
                    <a href="backup_process.php">
                        <button class="flex items-center gap-3 border-2 border-solid bg-[#0B483A] hover:bg-white text-white hover:text-[#0B483A] px-12 py-6 rounded-sm shadow transition duration-200">
                            <i data-lucide="cloud-upload" class="w-6 h-6"></i>
                            <span>Backup Now</span>
                        </button>
                    </a>
                </div>
            </div>


            <!-- RESTORE SECTION -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h2 class="text-2xl font-semibold text-green-800 mb-4 flex items-center gap-2">
                    <i data-lucide="rotate-cw" class="w-6 h-6"></i>Restore
                </h2>

                <div class="border-t pt-6">
                    <form action="restore_process.php" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">

                        <label class="flex items-center justify-start gap-2 px-14 py-4 bg-gray-300 text-gray-800 rounded-sm cursor-pointer hover:bg-gray-400 transition">
                            <i class="fa-solid fa-folder-open"></i>
                            <span>Choose File</span>
                            <input type="file" name="backup_file" class="hidden" required>
                        </label>


                        <button class="flex items-center gap-3 border-2 border-solid bg-[#0B483A] hover:bg-white text-white hover:text-[#0B483A] px-12 py-4 rounded-sm shadow transition duration-200">
                            <i data-lucide="cloud-sync" class="w-6 h-6"></i>
                            <span>Restore Now</span>
                        </button>

                    </form>
                </div>
            </div>

        </div>



    <script>
        lucide.createIcons();
    </script>

</body>
</html>