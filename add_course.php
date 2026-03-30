<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        input, textarea { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Course</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $course_name = $_POST['course_name'];
            $course_code = $_POST['course_code'];
            $description = $_POST['description'];
            $credits = $_POST['credits'];

            $sql = "INSERT INTO courses (course_name, course_code, description, credits) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $course_name, $course_code, $description, $credits);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Course added successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
        <form method="post" action="">
            <input type="text" name="course_name" placeholder="Course Name" required>
            <input type="text" name="course_code" placeholder="Course Code" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="number" name="credits" placeholder="Credits" required>
            <button type="submit">Add Course</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>