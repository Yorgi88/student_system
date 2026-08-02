<?php

require_once 'config/config.php';

// load the database class
require_once 'classes/database.php';

try{
    $pdo = Database::getConnection();
    echo "Data Connected Successfully!<br><br>";

    //show current database
    $stmt = $pdo->query("SELECT DATABASE() AS current_database");
    $result = $stmt->fetch();
    echo "Database: ". $result['current_database'] . "<br><br>";

    //show tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database: <br>";
    foreach($tables as $table){
        echo " - ". $table . "<br>";
    }
}catch(Exception $e){
    echo " -  Error". $e->getMessage(); 
}

$stmt = $pdo->query("SELECT * FROM students WHERE gpa > 3.0");
$rows = $stmt->fetchAll();
foreach($rows as $row){
    echo $row['first_name']. " ". $row['last_name']. " ". $row['gpa'] . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="search.php" action="post" 
     placeholder="enter matric no"
     value="<?php echo htmlspecialchars($_POST['matric_no'] ?? ''); ?>" required>
    <button type="submit">search</button>
    </form>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error;?></div>
    <?php endif; ?>

    <?php if(!empty($student['profile_image'])): ?>
        <img src="../<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Image">
    <?php endif;?>

    <div class="gpa">
        <?php if($student['gpa'] < 2.0): ?>
            <span class="score">Advised to Withdraw</span>
        <?php elseif($student['gpa'] >= 4.5): ?>
            <span>First Class</span>    
        <?php endif; ?>
    </div>
    
</body>
</html>