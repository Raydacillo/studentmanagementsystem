# Student Management System

A simple web-based student management system built with PHP and MySQL.

## Features

- Student login and dashboard
- Admin login and management panel
- Manage students, courses, enrollments, and grades
- View enrolled courses, grades, and attendance for students
- Record and manage attendance

## Setup Instructions

1. **Install XAMPP**: Make sure XAMPP is installed and running on your system.

2. **Database Setup**:
   - Start Apache and MySQL in XAMPP control panel.
   - Open phpMyAdmin (http://localhost/phpmyadmin).
   - Create a database named `student_management` (or run setup.php to create it automatically).

3. **Run Setup**:
   - Place all files in `C:\xampp\htdocs\studentmanagementsystem\`.
   - Open browser and go to `http://localhost/studentmanagementsystem/setup.php` to create tables and insert sample data.

4. **Access the System**:
   - Home: `http://localhost/studentmanagementsystem/index.php`
   - Student Login: `http://localhost/studentmanagementsystem/studentlogin.php`
   - Admin Login: `http://localhost/studentmanagementsystem/adminlogin.php`

## Default Accounts

- **Admin**: Username: `admin`, Password: `admin123`
- **Students**: 
  - Email: `john@example.com`, Password: `password`
  - Email: `jane@example.com`, Password: `password`

## Files Overview

- `config.php`: Database connection
- `setup.php`: Database and table creation
- `index.php`: Home page
- `studentlogin.php`: Student login
- `adminlogin.php`: Admin login
- `student_dashboard.php`: Student dashboard
- `admin_dashboard.php`: Admin dashboard
- `add_student.php`, `edit_student.php`, `delete_student.php`: Student management
- `add_course.php`, `edit_course.php`, `delete_course.php`: Course management
- `add_enrollment.php`, `edit_enrollment.php`, `delete_enrollment.php`: Enrollment management
- `add_grade.php`, `edit_grade.php`, `delete_grade.php`: Grade management
- `add_attendance.php`, `view_attendance.php`, `edit_attendance.php`, `delete_attendance.php`: Attendance management
- `logout.php`: Logout script

## Security Notes

- Prepared statements are used to prevent SQL injection.
- Sessions are used for authentication.

## Troubleshooting

- Ensure MySQL is running.
- Check database credentials in `config.php`.
- If setup.php doesn't work, manually create the database and run the SQL queries inside it.