<?php

class StudentAuth{
    // start the session if not started
    public static function startSession(): void{
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    public static function login(array $student): void{
        self::startSession();
        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_matric_no'] = $student['matric_no'];
        $_SESSION['student_data'] = $student;  //store all student data
    }

    public static function getMatricNo(): ?string{
        self::startSession();
        return $_SESSION['student_matric_no'] ?? null;

    }

    public static function isLoggedIn(): bool{
        self::startSession();
        return isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true;
    }

    public static function logout(): void{
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    public static function requireLogin(): void{
        self::startSession();
        if(!self::isLoggedIn()){
            header('Location: student_login.php');
            exit;
        }
    }
}

?>