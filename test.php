<?php 


$errors = [];


  // --- IMAGE UPLOAD ---
    $profile_image = null;
    if (empty($errors) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $maxFileSize = 2 * 1024 * 1024;
        if($_FILES['profile_image']['size'] > $maxFileSize){
            $errors[] = 'File too large';
        }
        $upload_dir = '../images/student_photos/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed)) {
            // Replace slashes in matric number
            $safe_matric = str_replace('/', '_', $matric_no);
            $new_filename = 'student_' . $safe_matric . '.' . $file_ext;
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





?>