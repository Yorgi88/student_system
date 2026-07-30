
<?php

class Admin {
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    /*
     find admin by username
    */
     public function findByUsername(string $username): ?array{
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $result = $stmt->fetch();
        return $result ? : null;
     }

     /*
      next we verify the admin password
     */
    public function verifyPassword(string $password, string $hash): bool{
        return password_verify($password, $hash);
    }

    /*
     lets create a feature that enables us to add
     */
    public function createAdmin(string $username, string $password, string $full_name, string $email): bool{
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO admins (username, 
        password_hash, full_name, email)
        VALUES (?,?,?,?)");
        return $stmt->execute([$username, $hash, $full_name, $email]);
    }
}

?>