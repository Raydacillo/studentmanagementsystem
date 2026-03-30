<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .menu { margin-bottom: 20px; }
        .menu a { display: inline-block; margin-right: 10px; padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .menu a:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .logout { text-align: right; margin-bottom: 20px; }
        .logout a { color: #dc3545; text-decoration: none; }
        .section { margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';
        ?>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
        <h2>Admin Dashboard</h2>

        <div class="menu">
            <a href="#students">Manage Students</a>
            <a href="#courses">Manage Courses</a>
            <a href="#enrollments">Manage Enrollments</a>
            <a href="#grades">Manage Grades</a>
            <a href="#attendance">Manage Attendance</a>
        </div>

        <div id="students" class="section">
            <h3>Students</h3>
            <a href="add_student.php">Add New Student</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roll Number</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM students";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['roll_number']) . "</td>";
                    echo "<td><a href='edit_student.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_student.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div id="courses" class="section">
            <h3>Courses</h3>
            <a href="add_course.php">Add New Course</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Credits</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM courses";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                    echo "<td>" . $row['credits'] . "</td>";
                    echo "<td><a href='edit_course.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_course.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div id="enrollments" class="section">
            <h3>Enrollments</h3>
            <a href="add_enrollment.php">Add Enrollment</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Enrollment Date</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT e.id, s.name as student_name, c.course_name, e.enrollment_date
                        FROM enrollments e
                        JOIN students s ON e.student_id = s.id
                        JOIN courses c ON e.course_id = c.id
                        ORDER BY e.enrollment_date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['enrollment_date']) . "</td>";
                    echo "<td><a href='edit_enrollment.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_enrollment.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div id="grades" class="section">
            <h3>Grades</h3>
            <a href="add_grade.php">Add Grade</a>
            <table>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT g.id, s.name as student_name, c.course_name, g.grade
                        FROM grades g
                        JOIN enrollments e ON g.enrollment_id = e.id
                        JOIN students s ON e.student_id = s.id
                        JOIN courses c ON e.course_id = c.id";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
                    echo "<td><a href='edit_grade.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_grade.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div id="attendance" class="section">
            <h3>Attendance</h3>
            <a href="add_attendance.php">Record Attendance</a> | <a href="view_attendance.php">View All Attendance</a>
            <table>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT a.id, s.name as student_name, c.course_name, a.attendance_date, a.status
                        FROM attendance a
                        JOIN students s ON a.student_id = s.id
                        JOIN enrollments e ON a.enrollment_id = e.id
                        JOIN courses c ON e.course_id = c.id
                        ORDER BY a.attendance_date DESC
                        LIMIT 10";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['attendance_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td><a href='edit_attendance.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_attendance.php?id=" . $row['id'] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center;'>No attendance records yet.</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>