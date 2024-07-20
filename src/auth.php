<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/firebase.php';

use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\AuthException;

class Auth
{
    private $auth;

    public function __construct()
    {
        global $auth;
        $this->auth = $auth;
    }

    public function register($email, $password)
    {
        try {
            $user = $this->auth->createUserWithEmailAndPassword($email, $password);
            return $user;
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            $_SESSION['user'] = $signInResult->data()['uid'];
            return true;
        } catch (AuthException $e) {
            return $e->getMessage();
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }

    public function isLoggedIn()
    {
        session_start();
        return isset($_SESSION['user']);
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->auth->getUser($_SESSION['user']);
        }
        return null;
    }
}

