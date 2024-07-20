<?php
session_start();

require_once  '../vendor/autoload.php';
require_once '../firebase.php';

use Kreait\Firebase\Exception\Auth\EmailExists;

$firebase = new Firebase();
$auth = $firebase->firebase_auth();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $userProperties = [
            'email' => $email,
            'emailVerified' => false,
            'password' => $password,
        ];

        $createdUser = $auth->createUser($userProperties);
        $_SESSION['user'] = $createdUser->email;
        header('Location: login.php');
        exit();
    } catch (EmailExists $e) {
        $message = 'The email address is already in use by another account.';
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        $message = 'Registration failed: ' . $e->getMessage();
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
        $message = 'Firebase error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl mb-6 text-center">Register</h2>
        <?php if ($message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700">Register</button>
        </form>
        <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-500">Login</a></p>
    </div>
</body>
</html>


