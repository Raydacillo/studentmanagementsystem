<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Attendance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        select, input { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Record Attendance</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $enrollment_id = $_POST['enrollment_id'];
            $attendance_date = $_POST['attendance_date'];
            $status = $_POST['status'];
            $remarks = $_POST['remarks'];

            // Validate that enrollment_id is selected
            if (empty($enrollment_id)) {
                echo "<p style='color: red; text-align: center;'>Please select a student and course.</p>";
            } else {
                // Get student_id from enrollment
                $sql_student = "SELECT student_id FROM enrollments WHERE id = ?";
                $stmt_student = $conn->prepare($sql_student);
                $stmt_student->bind_param("i", $enrollment_id);
                $stmt_student->execute();
                $result_student = $stmt_student->get_result();
                $row_student = $result_student->fetch_assoc();
                
                if (!$row_student) {
                    echo "<p style='color: red; text-align: center;'>Invalid enrollment selected.</p>";
                    $stmt_student->close();
                } else {
                    $student_id = $row_student['student_id'];

                    $sql = "INSERT INTO attendance (student_id, enrollment_id, attendance_date, status, remarks) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iisss", $student_id, $enrollment_id, $attendance_date, $status, $remarks);

                    if ($stmt->execute()) {
                        echo "<p style='color: green; text-align: center;'>Attendance recorded successfully!</p>";
                    } else {
                        echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                    $stmt_student->close();
                }
            }
        }
        ?>
        <form method="post" action="">
            <select name="enrollment_id" required>
                <option value="">Select Student and Course</option>
                <?php
                $sql = "SELECT e.id, s.name as student_name, c.course_name
                        FROM enrollments e
                        JOIN students s ON e.student_id = s.id
                        JOIN courses c ON e.course_id = c.id";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['student_name']) . " - " . htmlspecialchars($row['course_name']) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No enrollments available</option>";
                }
                ?>
            </select>
            <input type="date" name="attendance_date" required>
            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Late">Late</option>
            </select>
            <textarea name="remarks" placeholder="Remarks (optional)" style="margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            <button type="submit">Record Attendance</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
        <?php $conn->close(); ?>
    </div>
</body>
</html>