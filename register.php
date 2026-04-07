<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<form action="process_register.php" method="POST" 
      class="bg-white p-8 rounded-xl shadow w-[350px] space-y-4">

    <h1 class="text-2xl font-semibold text-center">Create Account</h1>

    <input type="text" name="name" placeholder="Name" required
        class="w-full border p-2 rounded">

    <input type="email" name="email" placeholder="Email" required
        class="w-full border p-2 rounded">

    <input type="password" name="password" placeholder="Password" required
        class="w-full border p-2 rounded">

    <button class="w-full bg-[#0B5C4A] text-white py-2 rounded">
        Register
    </button>

    <p class="text-sm text-center">
        Already have account? 
        <a href="login.php" class="text-blue-500">Login</a>
    </p>

</form>

</body>
</html>