<?php
session_start();

require_once  '../vendor/autoload.php';
require_once '../firebase.php';

// Check if the user is logged in
$loggedIn = isset($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">

    <!-- Navigation Bar -->
    <nav class="bg-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-lg font-bold">Blog App</a>
            <div>
                <?php if ($loggedIn): ?>
                    <a href="logout.php" class="text-blue-600 hover:text-blue-500">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-blue-600 hover:text-blue-500">Login</a>
                    <a href="register.php" class="ml-4 text-blue-600 hover:text-blue-500">Register</a>
                <?php endif; ?>
                <a href="browse_blog.php" class="ml-4 text-blue-600 hover:text-blue-500">Browse Blogs</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-6">Welcome to the Blog App</h1>
        <?php if ($loggedIn): ?>
            <p class="text-center">Welcome back, <?php echo htmlspecialchars($_SESSION['user']); ?>!</p>
            
            <!-- Optionally display recent blog posts -->
        <?php else: ?>
            <p class="text-center">Explore our latest blog posts or <a href="login.php" class="text-blue-600 hover:text-blue-500">log in</a> to create your own content.</p>
        <?php endif; ?>
    </div>

</body>
</html>
