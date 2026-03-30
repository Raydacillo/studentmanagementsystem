<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Enrollment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        select, button { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Enrollment</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $student_id = $_POST['student_id'];
            $course_id = $_POST['course_id'];

            $checkSql = "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $student_id, $course_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                echo "<p style='color: red; text-align: center;'>This student is already enrolled in that course.</p>";
            } else {
                $sql = "INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $student_id, $course_id);
                if ($stmt->execute()) {
                    echo "<p style='color: green; text-align: center;'>Enrollment added successfully!</p>";
                } else {
                    echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
                }
                $stmt->close();
            }
            $checkStmt->close();
        }
        ?>
        <form method="post" action="">
            <select name="student_id" required>
                <option value="">Select Student</option>
                <?php
                $students = $conn->query("SELECT id, name FROM students ORDER BY name");
                while ($row = $students->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
            <select name="course_id" required>
                <option value="">Select Course</option>
                <?php
                $courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name");
                while ($row = $courses->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                }
                ?>
            </select>
            <button type="submit">Add Enrollment</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
        <?php $conn->close(); ?>
    </div>
</body>
</html>