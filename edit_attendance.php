<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        select, input, textarea { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px; background: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #e0a800; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Attendance</h2>
        <?php
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header("Location: adminlogin.php");
            exit();
        }
        include 'config.php';

        $id = $_GET['id'];
        $sql = "SELECT * FROM attendance WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $attendance = $result->fetch_assoc();
        $stmt->close();

        if (!$attendance) {
            echo "<p style='color: red; text-align: center;'>Attendance record not found.</p>";
            $conn->close();
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $attendance_date = $_POST['attendance_date'];
            $status = $_POST['status'];
            $remarks = $_POST['remarks'];

            $sql = "UPDATE attendance SET attendance_date=?, status=?, remarks=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $attendance_date, $status, $remarks, $id);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Attendance updated successfully!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
        <form method="post" action="">
            <input type="date" name="attendance_date" value="<?php echo $attendance['attendance_date']; ?>" required>
            <select name="status" required>
                <option value="Present" <?php if ($attendance['status'] == 'Present') echo 'selected'; ?>>Present</option>
                <option value="Absent" <?php if ($attendance['status'] == 'Absent') echo 'selected'; ?>>Absent</option>
                <option value="Late" <?php if ($attendance['status'] == 'Late') echo 'selected'; ?>>Late</option>
            </select>
            <textarea name="remarks" placeholder="Remarks (optional)"><?php echo htmlspecialchars($attendance['remarks']); ?></textarea>
            <button type="submit">Update Attendance</button>
        </form>
        <div class="back">
            <a href="view_attendance.php">Back to Attendance</a>
        </div>
    </div>
</body>
</html>