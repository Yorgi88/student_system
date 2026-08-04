<?php
/*
 Auth handler, Manages login sessions
 */
 class Auth {
    public static function startSession(): void{
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    //next we log in an admin if the credentials correct
    public static function login(array $admin): void{
        self::startSession();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_full_name'] = $admin['full_name'];
        $_SESSION['admin_email'] = $admin['email'];
    }

    public static function isLoggedIn(): bool{
        self::startSession();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    //next is a method that gets admin full name
    public static function getAdminName(): ?string{
        self::startSession();
        return $_SESSION['admin_full_name'] ?? null;
    }

    //next meethod is to get admin username
    public static function getAdminUsername(): ?string{
        self::startSession();
        return $_SESSION['admin_username'] ?? null;
    }

    //next is the logout
    public static function logout(): void{
        self::startSession();
            $_SESSION = [];
            session_destroy();
    }

    //redirect to login method, if not logged in
    public static function requireLogin(): void{
        if(!self::isLoggedIn()){
            header("Location: login.php");
            exit;
        }
    }

 }
?>