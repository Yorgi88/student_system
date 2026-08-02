<?php

require_once '../config/config.php';
require_once '../classes/database.php';
require_once '../classes/auth.php';
require_once '../classes/student.php';

Auth::requireLogin();

$student = null;
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matric_no'])){
    $matric_no = trim($_POST['matric_no']);

    if(empty($matric_no)){
        $error =  'You need to enter a correct and valid matric no';
    }else{
        $get_student = new Student();
        $student = $get_student->findByMatric($matric_no);
        if(!$student){
            $error = 'NOT FOUND'. htmlspecialchars($matric_no);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
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
        .gpa-warning{
            color: #e74c3c;
            font-weight: bold;
        }
        .gpa-warning-first{
            color: #90ee90;
            font-weight: bold;
        }
        @media (max-width: 600px) {
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
        <div class="header">
            <h1>🔍 Search Student</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <form method="POST" action="search.php">
            <div class="search-box">
                <input type="text" name="matric_no" placeholder="Enter Matric Number..." 
                       value="<?php echo htmlspecialchars($_POST['matric_no'] ?? ''); ?>" required>
                <button type="submit">Search</button>
            </div>
        </form>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($student): ?>
            <div class="student-details">
                <h2>📋 Student Details</h2>


                
                <?php if (!empty($student['profile_image'])): ?>
                    <img src="../<?php echo htmlspecialchars($student['profile_image']); ?>" 
                         alt="Profile Image" style="max-width:100px;border-radius:50%;margin-bottom:15px;">
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
                            <span class="gpa-warning">⚠️ Advise to Withdraw</span>
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