<?php

require_once '../config/config.php';
require_once '../classes/database.php';
require_once '../classes/student_auth.php';
require_once '../classes/student.php';

StudentAuth::requireLogin();
$student = $_SESSION['student_data'] ?? null;

if(!$student){
    StudentAuth::logout();
    header("Location: student_login.php");
}

// get student_name for the welcom message
$studentName = $student['last_name']. ' '. $student['first_name'];




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto 0 auto;
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
            font-size: 1.5rem;
        }
        .welcome {
            color: #2d7d46;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .back-btn {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #2980b9;
        }
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #bdc3c7;
            border-radius: 6px;
            font-size: 16px;
        }
        .search-box button {
            padding: 12px 24px;
            background: #2d7d46;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .search-box button:hover {
            background: #1e5c32;
        }
        .error {
            color: #e74c3c;
            padding: 10px;
            background: #fde8e8;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .student-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .student-details h2 {
            color: #1a3c5e;
            margin-bottom: 15px;
        }
        .profile-img-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-img {
            max-width: 120px;
            border-radius: 50%;
            border: 3px solid #2d7d46;
            padding: 3px;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .gpa-warning {
            color: #e74c3c;
            font-weight: bold;
        }
        .gpa-warning-first {
            color: #27ae60;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .search-box {
                flex-direction: column;
            }
            .detail-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Welcome Message and Logout -->
        <div class="header">
            <h1>👋 Welcome, <span class="welcome"><?php echo htmlspecialchars($studentName); ?></span>!</h1>
            <a href="student_logout.php" class="logout-btn">🚪 Logout</a>
        </div>
  
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($student): ?>
            <div class="student-details">
                <h2>📋 My Profile</h2>

                <?php if (!empty($student['profile_image'])): ?>
                    <div class="profile-img-container">
                        <img src="../<?php echo htmlspecialchars($student['profile_image']); ?>" 
                             alt="Profile Image" class="profile-img">
                    </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <span class="detail-label">Matric No:</span>
                    <span><?php echo htmlspecialchars($student['matric_no']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Full Name:</span>
                    <span><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">GPA:</span>
                    <span>
                        <?php echo htmlspecialchars($student['gpa']); ?>
                        <?php if ($student['gpa'] < 2.0): ?>
                            <span class="gpa-warning">⚠️ Advised to Withdraw</span>
                        <?php elseif ($student['gpa'] >= 4.5): ?> 
                            <span class="gpa-warning-first">✅ First Class</span>   
                        <?php endif; ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date of Birth:</span>
                    <span><?php echo htmlspecialchars($student['date_of_birth']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nationality:</span>
                    <span><?php echo htmlspecialchars($student['nationality'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">State of Origin:</span>
                    <span><?php echo htmlspecialchars($student['state_of_origin'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Local Government:</span>
                    <span><?php echo htmlspecialchars($student['local_government'] ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span><?php echo htmlspecialchars($student['email']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span><?php echo htmlspecialchars($student['phone_no'] ?? 'N/A'); ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
