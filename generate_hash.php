<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "<br>";
echo "New Hash: " . $hash . "<br><br>";
echo "Copy this hash into MySQL Workbench:";
?>