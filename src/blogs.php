<?php
require 'vendor/autoload.php';
use Kreait\Firebase\Factory;

$firebase = (new Factory)
    ->withServiceAccount(__DIR__.'/config/blogapp-554b0-firebase-adminsdk-blpwb-ad5a3dcb12.json')
    ->create();

$database = $firebase->getDatabase();
$blogs = $database->getReference('blogs')->getValue();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Blog Posts</h1>
        <?php foreach ($blogs as $blog): ?>
            <div class="bg-white p-4 mb-4 rounded shadow-md">
                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($blog['title']); ?></h2>
                <p><?php echo htmlspecialchars($blog['content']); ?></p>
                <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="Blog Image" class="mt-2 w-full max-w-sm">
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
