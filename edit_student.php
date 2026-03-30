<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        input, textarea { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #e0a800; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        $id = $_GET['id'];
        $sql = "SELECT * FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $roll_number = $_POST['roll_number'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            $sql = "UPDATE students SET name=?, email=?, roll_number=?, phone=?, address=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $email, $roll_number, $phone, $address, $id);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Student updated successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
        <form method="post" action="">
            <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            <input type="text" name="roll_number" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
            <textarea name="address"><?php echo htmlspecialchars($student['address']); ?></textarea>
            <button type="submit">Update Student</button>
        </form>
        <div class="back">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>