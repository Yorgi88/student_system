<?php
// Load required files
require_once 'config/config.php';
require_once 'classes/database.php';
require_once 'classes/admin.php';

try {
    echo "=== PASSWORD VERIFICATION TEST ===<br><br>";
    
    // 1. Test the hash directly
    $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    $password = 'admin123';
    
    echo "1. Testing hash directly:<br>";
    echo "   Password: " . $password . "<br>";
    echo "   Hash: " . $hash . "<br>";
    
    $result = password_verify($password, $hash);
    echo "   Result: " . ($result ? '✅ MATCHES!' : '❌ DOES NOT MATCH') . "<br><br>";
    
    // 2. Test the database lookup
    echo "2. Testing database lookup:<br>";
    $adminModel = new Admin();
    $admin = $adminModel->findByUsername('admin');
    
    if ($admin) {
        echo "   ✅ Admin found!<br>";
        echo "   Username: " . $admin['username'] . "<br>";
        echo "   Full Name: " . $admin['full_name'] . "<br>";
        echo "   Stored hash: " . $admin['password_hash'] . "<br>";
        
        // 3. Test password_verify with database hash
        $db_result = password_verify($password, $admin['password_hash']);
        echo "   password_verify() result: " . ($db_result ? '✅ MATCHES!' : '❌ DOES NOT MATCH') . "<br>";
        
        if ($db_result) {
            echo "<br><strong>✅ SUCCESS! Your login should work!</strong>";
        } else {
            echo "<br><strong>❌ The hash in the database doesn't match 'admin123'</strong>";
        }
    } else {
        echo "   ❌ Admin NOT found in database!<br>";
        echo "   Run the INSERT query again.";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>