<?php
require_once '../config/config.php';
require_once '../classes/database.php';
require_once '../classes/auth.php';

//protect this page
Auth::requireLogin();

$adminName = Auth::getAdminName();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2d7d46;
            padding-bottom: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .header h1 {
            color: #1a3c5e;
            margin: 0;
        }
        .welcome {
            color: #2d7d46;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .card h3 {
            margin-top: 0;
            color: #1a3c5e;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header with Welcome Message -->
        <div class="header">
            <h1>👋 Welcome, <span class="welcome"><?php echo htmlspecialchars($adminName); ?></span>!</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Dashboard Content -->
        <div class="card">
            <h3>📊 Quick Actions</h3>
            <p>Welcome to the Student Management System.</p>
            <p>Here you can manage student records, view reports, and more.</p>
        </div>

        <div class="card">
            <h3>🔍 Search Student</h3>
            <p>Search for a student by their Matric Number.</p>
            <!-- We'll add search functionality next -->
        </div>

        <div class="card">
            <h3>➕ Add New Student</h3>
            <p>Create a new student entry in the system.</p>
            <!-- We'll add create functionality next -->
        </div>
    </div>
</body>
</html>