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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .dashboard-container {
            max-width: 900px;
            margin:60px auto 0 auto;
            background: white;
            padding: 40px;
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

        /* Card Grid */
        .card-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #f8f9fa;
            padding: 30px 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: #2d7d46;
            background: #ffffff;
        }

        .card h3 {
            font-size: 1.5rem;
            color: #1a3c5e;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 0.95rem;
        }

        .card-icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
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

        <!-- Clickable Cards -->
        <div class="card-grid">
            <!-- Search Student Card -->
            <a href="search.php" class="card">
                <span class="card-icon">🔍</span>
                <h3>Search Student</h3>
                <p>Find a student by their Matric Number.</p>
            </a>

            <!-- Add New Student Card -->
            <a href="create.php" class="card">
                <span class="card-icon">➕</span>
                <h3>Add New Student</h3>
                <p>Create a new student entry in the system.</p>
            </a>
        </div>
    </div>
</body>
</html>