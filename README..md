We need to connect to our MySQL workbench application first

- MAKE SURE YOU USE THE THREADED VERSION OF PHP NOT NON-THREADED
- We made use of Apache webserver in this project after series of configurations
- with the help of AI

- Open the MySQL workbench, click on the + icon
- Add "student_system" to the connection name
- in the password section, click "Store in vault"
- enter your MySQL root password
- next, you click on test connection, -> then click OK to session_save_path
- You see a student_system icon after, double click on it 
- This takes you to your SQL editor
- Add this code to the editor: 

CREATE DATABASE IF NOT EXISTS student_system;
USE student_system;
SELECT DATABASE() AS current_database;

- ..."as current_database" shows you the database you're currently working on
- Next, we want to create Admins and Students table

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matric_no VARCHAR(50) UNIQUE NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gpa DECIMAL(3,2) NOT NULL,
    nationality VARCHAR(100),
    state_of_origin VARCHAR(100),
    local_government VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_no VARCHAR(20),
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

run the code

- Next, we want to insert a test admin so we can log in later
- also, we insert into the student table (2 dummy students)

INSERT INTO admins (username, password_hash, full_name, email) 
VALUES (
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'System Administrator', 
    'admin@school.edu'
);

INSERT INTO students (
    matric_no, last_name, first_name, date_of_birth, gpa,
    nationality, state_of_origin, local_government, email, phone_no
) VALUES (
    'MAT/2024/001', 'Okafor', 'Chidi', '2002-05-15', 3.75,
    'Nigerian', 'Lagos', 'Eti-Osa', 'chidi.okafor@student.edu', '08012345678'
);

INSERT INTO students (
    matric_no, last_name, first_name, date_of_birth, gpa,
    nationality, state_of_origin, local_government, email, phone_no
) VALUES (
    'MAT/2024/002', 'Adeyemi', 'Folake', '2003-08-22', 1.85,
    'Nigerian', 'Oyo', 'Ibadan North', 'folake.adeyemi@student.edu', '08087654321'
);

- for the password hash in the one we inserted in the Admins, the tranlation
is admin123 as password

- test the tables you just populated

SELECT * FROM student_system.students; - do the same for Admins table

- Next, head to your config.php file in the config dir and set
- remember to use .gitignore for the config file
- see config.php
- after setting, go to the classes/database.php file
- to create the database class
- see database.php

- the database.php enables us to create a singleton class that helps us get
- a connection to the database if $instance is null
- the getConnection() method ensures a PDO object will be returned

- create a test_connection.php file in the root dir to test

- see the test_connect.php file

- we see it works, after various configurations
- lets push what we did to github and then continue.

- next, we want to create the login/auth system for the admin
- so, in the admin dir, create login, logout and dashboard.php files
- in the classes dir, create the admin.php and auth.php too

- How the whole auth thing works
- when the admin, types in the username, and the password
- php checks the username if it matches the one in the db
- php then check the password of that username in the db
- if it matches , php creates a session, like a digital id card
- and logs the person in, if not, throws and error, 'incorrect username or password'

- we start with the admin.php defining our admin model
- see admin.php

- next we go to auth.php and create the Auth model, see auth.php
- auth.php acts as the security that checks your id before you can enter a place

- various methods like start startSession(), login() etc

- next, move to the admin/login.php

- see the admin/login.php file, the code is quite clean and self_explanatory
- we also added the html and css, we'll move the css into a separate dir soon
- for now, we test!

- next, move to the admin/dashboard.php
- see dashboard.php

- next, go to the admin/logout.php
dedded
dededed
ede

- the dashboard.php is done, the admin sees the search student featuer
- and also the create new student feature, as long as the admin is autheticated

- next, we actually build the search feature
- first , in the classes/ dir, we added a student.php
- see student.php
- we added the methods for getting a student by their their matric_no
- second method is for creating a new student

- next, create a search.php file in the admin dir
- see search.php, we've finished the search feature
- now to the create entry feature
- 
- look at admin/create.php
- 
- in the create.php we're currently in the file uploads section
- look at the file upload code, it follows this principles

- Checks if a file was actually uploaded
- Creates the upload folder if it doesn't exist
- Validates the file type (only images!)
- Renames the file safely
- Moves it to the right place
- Stores the path for later use

-