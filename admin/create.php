<?php 

require_once '../config/config.php';
require_once '../classes/database.php';
require_once '../classes/auth.php';
require_once '../classes/student.php';

Auth::requireLogin();

$error = '';
$success = '';
$studentData = [];

//the entire form logic
//- THOUGHT PROCESS
// - Get the form data, store the form data in the studeData
// - check for empty fields
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_student'])){
    $matric_no = trim($_POST['matric_no'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name']?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gpa = $_POST['gpa'] ?? '';
    $nationality = trim($_POST['nationality'] ?? '');
    $state_of_origin = trim($_POST['state_of_origin'] ?? '');
    $local_government = trim($_POST['local_government'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_no = trim($_POST['phone_no'] ?? '');

    //store em in the studentData array
    $studentData = [
        'matric_no' => $matric_no,
        'last_name' => $last_name,
        'first_name' => $first_name,
        'date_of_birth' => $date_of_birth,
        'gpa' => $gpa,
        'nationality' => $nationality,
        'state_of_origin' => $state_of_origin,
        'local_government' => $local_government,
        'email' => $email,
        'phone_no' => $phone_no
    ];

    $errors = [];

    //logic for checking empty fields
    if(empty($matric_no)) $errors[] = 'matric_no field empty';
    if(empty($last_name)) $errors[] = 'last name field empty';
    if(empty($first_name)) $errors[] = 'first name field empty';
    if (empty($date_of_birth)) $errors[] = "Date of Birth is required.";
    if ($gpa === '' || $gpa === null) $errors[] = "GPA is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if($phone_no === '' || $phone_no === null) $errors[] = 'Phone no required';

    //gpa validation range
    if(!empty($gpa) && ($gpa < 1.0 || $gpa > 5.0)){
        $errors[] = 'invalid GPA';
    }

    //email validation
    if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Invalid email format';
    }

    //check for duplicate matric_no's
    if(empty($errors)){
        $student_model = new Student();
        $existing_student = $student_model->findByMatric($matric_no);
        if($existing_student){
            $errors[] = "matric number '{$matric_no}' already exists!";
        }
    }

    //image uploads
    $profile_image = null;

    if (empty($errors) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/student_photos/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = 'student_' . str_replace('/', '_', $matric_no) . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                $profile_image = 'images/student_photos/' . $new_filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Invalid image format. Allowed: JPG, PNG, GIF, WEBP.";
        }
    }
    
    // --- INSERT INTO DATABASE ---
    if (empty($errors)) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO students (
                    matric_no, last_name, first_name, date_of_birth, gpa,
                    nationality, state_of_origin, local_government, email, phone_no,
                    profile_image
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $matric_no,
                $last_name,
                $first_name,
                $date_of_birth,
                $gpa,
                $nationality ?: null,
                $state_of_origin ?: null,
                $local_government ?: null,
                $email,
                $phone_no ?: null,
                $profile_image
            ]);
            
            $success = "✅ Student '{$first_name} {$last_name}' added successfully!";
            $studentData = []; // Clear form data on success
            
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = implode("<br>", $errors);
    }
    
}

$admin_name = Auth::getAdminName();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new student</title>
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
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .header h1 {
            color: #1a3c5e;
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
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            font-size: 0.9rem;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #bdc3c7;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2d7d46;
            box-shadow: 0 0 0 3px rgba(45, 125, 70, 0.1);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .btn-create {
            padding: 12px 30px;
            background: #2d7d46;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-create:hover {
            background: #1e5c32;
        }
        .btn-cancel {
            padding: 12px 30px;
            background: #95a5a6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #7f8c8d;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .required {
            color: #e74c3c;
        }
        .file-input-wrapper {
            position: relative;
        }
        .file-input-wrapper input[type="file"] {
            padding: 10px 0;
        }
        small {
            color: #7f8c8d;
            font-size: 0.8rem;
            display: block;
            margin-top: 4px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .form-actions {
                flex-direction: column;
            }
            .form-actions button,
            .form-actions a {
                width: 100%;
                text-align: center;
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
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>➕ Add New Student</h1>
            <a href="dashboard.php" class="back-btn">← Dashboard</a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form -->
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="matric_no">Matric Number <span class="required">*</span></label>
                        <input type="text" id="matric_no" name="matric_no" 
                               value="<?php echo htmlspecialchars($studentData['matric_no'] ?? ''); ?>" 
                               placeholder="e.g., MAT/2024/001" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" 
                               value="<?php echo htmlspecialchars($studentData['last_name'] ?? ''); ?>" 
                               placeholder="e.g., Okafor" required>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" 
                               value="<?php echo htmlspecialchars($studentData['first_name'] ?? ''); ?>" 
                               placeholder="e.g., Chidi" required>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth <span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" 
                               value="<?php echo htmlspecialchars($studentData['date_of_birth'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gpa">GPA <span class="required">*</span> (0.00 - 4.00)</label>
                        <input type="number" id="gpa" name="gpa" step="0.01" min="0" max="4.00" 
                               value="<?php echo htmlspecialchars($studentData['gpa'] ?? ''); ?>" 
                               placeholder="e.g., 3.75" required>
                        <small>Enter GPA between 0.00 and 4.00</small>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality" 
                               value="<?php echo htmlspecialchars($studentData['nationality'] ?? ''); ?>" 
                               placeholder="e.g., Nigerian">
                    </div>

                    <div class="form-group">
                        <label for="state_of_origin">State of Origin</label>
                        <input type="text" id="state_of_origin" name="state_of_origin" 
                               value="<?php echo htmlspecialchars($studentData['state_of_origin'] ?? ''); ?>" 
                               placeholder="e.g., Lagos">
                    </div>

                    <div class="form-group">
                        <label for="local_government">Local Government</label>
                        <input type="text" id="local_government" name="local_government" 
                               value="<?php echo htmlspecialchars($studentData['local_government'] ?? ''); ?>" 
                               placeholder="e.g., Eti-Osa">
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($studentData['email'] ?? ''); ?>" 
                               placeholder="e.g., student@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_no">Phone Number</label>
                        <input type="tel" id="phone_no" name="phone_no" 
                               value="<?php echo htmlspecialchars($studentData['phone_no'] ?? ''); ?>" 
                               placeholder="e.g., 08012345678">
                    </div>

                    <div class="form-group">
                        <label for="profile_image">Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                        <small>Allowed: JPG, PNG, GIF, WEBP (Max 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" name="create_student" class="btn-create">➕ Create Entry</button>
                <a href="dashboard.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>