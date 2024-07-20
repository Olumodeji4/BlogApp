<?php
session_start();

require_once  '../vendor/autoload.php';
require_once '../firebase.php';
if(!isset($_SESSION['user'])){
    header('Location:login.php');
}
use Kreait\Firebase\Database;
use Kreait\Firebase\Storage;
$firebase = new Firebase();

$database = $firebase->get_firebase();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Handle file upload
    $imageUrl= $firebase->upload_image('image');
    
    // Save the blog post to Firebase Database
    $blogPost = [
        'title' => $title,
        'content' => $content,
        'image' => $imageUrl
    ];
    
    try {
        $firebase->update_blog($blogPost);
        $message = 'Blog post uploaded successfully!';
    } catch (\Exception $e) {
        $message = 'Failed to upload blog post: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Upload Blog Post</h2>
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>
        <form action="upload_blog.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700">Upload</button>
        </form>
        <p class="mt-4 text-center"><a href="browse_blog.php" class="text-indigo-600 hover:text-indigo-500">Browse Blogs</a></p>
    </div>
</body>
</html>


