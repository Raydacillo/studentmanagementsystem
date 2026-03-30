<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grade</title>
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
        <h2>Add Grade</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $enrollment_id = $_POST['enrollment_id'];
            $grade = $_POST['grade'];

            $sql = "INSERT INTO grades (enrollment_id, grade) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $enrollment_id, $grade);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Grade added successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        ?>
        <form method="post" action="">
            <select name="enrollment_id" required>
                <option value="">Select Enrollment</option>
                <?php
                $sql = "SELECT e.id, s.name as student_name, c.course_name
                        FROM enrollments e
                        JOIN students s ON e.student_id = s.id
                        JOIN courses c ON e.course_id = c.id
                        LEFT JOIN grades g ON e.id = g.enrollment_id
                        WHERE g.id IS NULL"; // Only unenrolled or ungraded
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['student_name']) . " - " . htmlspecialchars($row['course_name']) . "</option>";
                }
                ?>
            </select>
            <input type="text" name="grade" placeholder="Grade (e.g., A, B+, 85)" required>
            <button type="submit">Add Grade</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
        <?php $conn->close(); ?>
    </div>
</body>
</html>