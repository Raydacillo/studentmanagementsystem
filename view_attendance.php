<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .back { text-align: right; margin-bottom: 20px; }
        .back a { color: #007bff; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .present { color: green; }
        .absent { color: red; }
        .late { color: orange; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Records</h2>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
        <table>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
            <?php
            session_start();
            if (!isset($_SESSION['admin_id'])) {
                header("Location: adminlogin.php");
                exit();
            }
            include 'config.php';

            $sql = "SELECT a.id, s.name as student_name, c.course_name, a.attendance_date, a.status, a.remarks
                    FROM attendance a
                    JOIN students s ON a.student_id = s.id
                    JOIN enrollments e ON a.enrollment_id = e.id
                    JOIN courses c ON e.course_id = c.id
                    ORDER BY a.attendance_date DESC";
            
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status_class = strtolower($row['status']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['attendance_date']) . "</td>";
                    echo "<td class='" . $status_class . "'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['remarks']) . "</td>";
                    echo "<td><a href='edit_attendance.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_attendance.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center;'>No attendance records found.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>