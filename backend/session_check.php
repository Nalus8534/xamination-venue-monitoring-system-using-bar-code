<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\session_check.php -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../frontend/index.php');
    exit();
}
?>