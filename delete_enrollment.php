<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}
include 'config.php';

$id = $_GET['id'];
$sql = "DELETE FROM enrollments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error deleting enrollment: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>