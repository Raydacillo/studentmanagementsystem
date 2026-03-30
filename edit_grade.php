<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grade</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        input { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #e0a800; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Grade</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        $id = $_GET['id'];
        $sql = "SELECT g.grade FROM grades g WHERE g.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $grade_row = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $grade = $_POST['grade'];

            $sql = "UPDATE grades SET grade=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $grade, $id);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Grade updated successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
        <form method="post" action="">
            <input type="text" name="grade" value="<?php echo htmlspecialchars($grade_row['grade']); ?>" required>
            <button type="submit">Update Grade</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>