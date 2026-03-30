<?php
include 'config.php';

// SQL to create tables
$sql = "
CREATE DATABASE IF NOT EXISTS student_management;

USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    credits INT NOT NULL
);

CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    grade VARCHAR(5),
    grade_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    enrollment_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late') NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin
INSERT IGNORE INTO admins (username, password) VALUES ('admin', 'admin123');

-- Insert sample data
INSERT IGNORE INTO students (name, email, password, roll_number, phone, address) VALUES
('John Doe', 'john@example.com', 'password', 'STU001', '1234567890', '123 Main St'),
('Jane Smith', 'jane@example.com', 'password', 'STU002', '0987654321', '456 Elm St');

INSERT IGNORE INTO courses (course_name, course_code, description, credits) VALUES
('Mathematics', 'MATH101', 'Basic Mathematics', 3),
('Physics', 'PHYS101', 'Introduction to Physics', 4),
('Computer Science', 'CS101', 'Programming Fundamentals', 3);

INSERT IGNORE INTO enrollments (student_id, course_id) VALUES
(1, 1), (1, 2), (2, 2), (2, 3);

INSERT IGNORE INTO attendance (student_id, enrollment_id, attendance_date, status) VALUES
(1, 1, CURDATE(), 'Present'),
(1, 1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Present'),
(2, 3, CURDATE(), 'Absent'),
(2, 3, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Late');
";

if ($conn->multi_query($sql) === TRUE) {
    echo "Database and tables created successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>