<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\login.php -->
<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the entered password
    $hashed_password = md5($password);

    // Check credentials in the database
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php'); // Redirect to the dashboard
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='../frontend/index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>