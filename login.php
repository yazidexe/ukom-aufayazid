<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<form action="process_login.php" method="POST" 
      class="bg-white p-8 rounded-xl shadow w-[350px] space-y-4">

    <h1 class="text-2xl font-semibold text-center">Login</h1>

    <input type="email" name="email" placeholder="Email" required
        class="w-full border p-2 rounded">

    <input type="password" name="password" placeholder="Password" required
        class="w-full border p-2 rounded">

    <button class="w-full bg-[#0B5C4A] text-white py-2 rounded">
        Login
    </button>

    <p class="text-sm text-center">
        Don't have account? 
        <a href="register.php" class="text-blue-500">Register</a>
    </p>

</form>

</body>
</html>