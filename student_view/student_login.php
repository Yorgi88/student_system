<?php

require_once '../config/config.php';
require_once '../classes/database.php';
require_once '../classes/student.php';
require_once '../classes/student_auth.php';

if(StudentAuth::isLoggedIn()){
    header("Location: student_dashboard.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matric_no'])){
    $matric_no = trim($_POST['matric_no'] ?? '');
    $password = $_POST['password'] ?? '';

    $error = '';

    if(empty($matric_no) || empty($password)){
        $error = 'Please, enter both matric_no and password';
    }else{
        $student_model = new Student();
        $student = $student_model->findByMatric($matric_no);

        if($student && $student_model->verifyPassword($password, $student['password_hash'])){
            StudentAuth::login($student);
            header("Location: student_dashboard.php");
            exit;
        }else{
            $error = 'Invalid matric_no or password';
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student logs</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* Quick styles for login page */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            text-align: center;
            color: #1a3c5e;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2d7d46;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-login:hover {
            background: #1e5c32;
        }
        .error {
            color: #e74c3c;
            padding: 10px;
            background: #fde8e8;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>📚 Student Login</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="student_login.php">
            <div class="form-group">
                <label for="matric_no">Matric No</label>
                <input type="text" id="matric_no" name="matric_no" 
                       value="<?php echo htmlspecialchars($_POST['matric_no'] ?? ''); ?>" 
                       placeholder="Enter matric_no" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Enter password" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>