<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .welcome { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .logout { text-align: right; margin-bottom: 20px; }
        .logout a { color: #dc3545; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        if (!isset($_SESSION['student_id'])) {
            header("Location: studentlogin.php");
            exit();
        }
        include 'config.php';
        $student_id = $_SESSION['student_id'];
        $student_name = $_SESSION['student_name'];
        ?>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
        <h2>Student Dashboard</h2>
        <div class="welcome">
            <p>Welcome, <?php echo htmlspecialchars($student_name); ?>!</p>
        </div>

        <h3>Your Profile</h3>
        <?php
        $sql = "SELECT * FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>

        <h3>Your Enrolled Courses</h3>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Credits</th>
                <th>Grade</th>
            </tr>
            <?php
            $sql = "SELECT c.course_name, c.course_code, c.credits, g.grade
                    FROM enrollments e
                    JOIN courses c ON e.course_id = c.id
                    LEFT JOIN grades g ON e.id = g.enrollment_id
                    WHERE e.student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                echo "<td>" . htmlspecialchars($row['credits']) . "</td>";
                echo "<td>" . htmlspecialchars($row['grade'] ?? 'Not graded') . "</td>";
                echo "</tr>";
            }
            $stmt->close();
            ?>
        </table>

        <h3>Your Attendance</h3>
        <table>
            <tr>
                <th>Course</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php
            $sql = "SELECT c.course_name, a.attendance_date, a.status
                    FROM attendance a
                    JOIN enrollments e ON a.enrollment_id = e.id
                    JOIN courses c ON e.course_id = c.id
                    WHERE a.student_id = ?
                    ORDER BY a.attendance_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['attendance_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No attendance records found.</td></tr>";
            }
            $stmt->close();
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>