<?php

class Student{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    // find student by matric number
    public function findByMatric(string $matric_no){
        $stmt = $this->db->prepare("SELECT * FROM students WHERE matric_no = ?");
        $stmt->execute([$matric_no]);
        $result = $stmt->fetch();
        return $result ? : null;
    }

    // method to create new entry || new student 
    public function createEntry(array $data): bool{
        $stmt = $this->db->prepare("
        INSERT INTO students (
          matric_no, last_name, first_name, date_of_birth, gpa,
            nationality, state_of_origin, local_government, email, phone_no,
            profile_image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['matric_no'],
            $data['last_name'],
            $data['first_name'],
            $data['date_of_birth'],
            $data['gpa'],
            $data['nationality'] ?? null,
            $data['state_of_origin'] ?? null,
            $data['local_government'] ?? null,
            $data['email'],
            $data['phone_no'] ?? null,
            $data['profile_image'] ?? null
        ]);
    }
}

?>