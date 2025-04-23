<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\logout.php -->
<?php
session_start();
session_destroy();
header('Location: ../frontend/index.php'); // Redirect to login page
exit();
?>