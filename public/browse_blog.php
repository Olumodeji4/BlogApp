<?php
session_start();


$message ='';$type='error';
require_once  '../vendor/autoload.php';
require_once '../firebase.php';


$firebase = new Firebase();

$blogsReference = $firebase->get_blog_database();

// protecting the delete route to only allow the right session to delete post
if(isset($_POST['_delete'])&&$_POST['_delete'] &&($_POST['_delete']===session_id())){

    try{
        $blogId = $_POST['blogID']??'';
        $firebase->delete_blog($blogId);
        $message='Blog Deleted Successfully';
        $type = 'success';

    }catch (Throwable $e){
        $message=' Unable to Delete Blog Post';
    }
}

$blogs = $blogsReference->getValue();

// Check if the user is logged in
$loggedIn = isset($_SESSION['user']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
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
            <a href="upload_blog.php" class="ml-4 text-blue-600 hover:text-blue-500">Upload Blogs</a>
        </div>
    </div>
</nav>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Browse Blogs</h1>
        <?php if (isset($message)&& $message): ?>
            <div class=" <?php if($type=='success'){ echo 'bg-green-100 border border-green-400 text-green-700'; }else{ echo 'bg-red-100 border border-red-400 text-red-700';}  ?> px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($blogs): ?>
            <?php foreach ($blogs as $blogId => $blog): ?>
                <div class="bg-white p-4 mb-4 rounded shadow-md relative p-2">
                    <?php if (isset($_SESSION['user'])): ?>
                    <div class="absolute right-1 top-1" onclick="confirmDelete('<?php echo $blogId ?>')">
                        <button class="bg-red-700 rounded-lg active:bg-red-800 text-white p-2">X</button>
                        <?php endif; ?>
                    </div>

                    <h2 class="text-xl font-semibold">
                        <?php echo htmlspecialchars($blog['title']); ?></h2>
                    <p class="mt-2"><?php echo htmlspecialchars($blog['content']); ?></p>
                    <?php if (isset($blog['image'])): ?>
                        <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="mt-2 w-full max-w-lg">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">No blogs available.</p>
        <?php endif; ?>
        <form id="blogDeleter" action="browse_blog.php" method="post">
            <input type="hidden" name="_delete" value="<?php echo session_id() ?>">
            <input type="hidden" id="deleteBlogId" name="blogID">
        </form>
    </div>
<script>
    let confirmDelete = (blogId)=> {
        const confirmed = confirm('Are you sure you want to delete this blog?');
        if (confirmed) {
            let el = document.getElementById('deleteBlogId');
            el.value = blogId
            document.getElementById('blogDeleter').submit()
        }
    }
</script>
</body>
</html>
