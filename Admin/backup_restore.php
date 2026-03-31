<?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div id="successAlert"
         class="fixed top-6 right-[200px] translate-y-[-100px] opacity-0 
                bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg 
                flex items-center gap-3 transition-all duration-500">

        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>Backup berhasil dibuat!</span>
    </div>

    <script>
        const alertBox = document.getElementById("successAlert");

        // Slide down
        setTimeout(() => {
            alertBox.classList.remove("translate-y-[-100px]", "opacity-0");
        }, 100);

        // Hide after 4 seconds
        setTimeout(() => {
            alertBox.classList.add("translate-y-[-100px]", "opacity-0");
        }, 2000);
    </script>
<?php endif; ?>


<?php 
$files = glob("../backup/*.sql");
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
<body>
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">

        <!-- Header + Back Button -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-[#0B483A] flex items-center gap-2">
                <i data-lucide="database" class="w-6 h-6"></i>
                
                Backup Files
            </h2>

            <a href="backup.php"
            class="bg-gray-200 text-gray-800 px-8 py-2 rounded-bl-2xl font-medium hover:opacity-90 transition">
                back
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="border-b text-gray-600 uppercase text-xs tracking-wider">
                        <th class="py-3">File Name</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Size</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    <?php foreach($files as $file): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 font-medium text-gray-800">
                            <?= basename($file); ?>
                        </td>

                        <td class="py-4 text-gray-600">
                            <?= date("d M Y - H:i", filemtime($file)); ?>
                        </td>

                        <td class="py-4 text-gray-600">
                            <?= round(filesize($file)/1024, 2) . " KB"; ?>
                        </td>

                        <td class="py-4">
                            <div class="flex justify-center gap-3">

                                <a href="../backup/<?= basename($file); ?>" download
                                class="px-4 py-2 bg-[#61C38D] text-white rounded-sm hover:bg-green-600 transition text-sm">
                                <i data-lucide="file-down" class="w-6 h-6"></i>
                                </a>

                                <a href="backup_delete.php?file=<?= basename($file); ?>"
                                class="px-4 py-2 bg-[#E54B4B] text-white rounded-sm hover:bg-red-600 transition text-sm">
                                <i data-lucide="trash" class="w-6 h-6"></i>
                                
                                </a>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>