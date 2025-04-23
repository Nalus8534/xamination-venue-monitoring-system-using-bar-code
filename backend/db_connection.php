<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examination_venue";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // **Critical:** DO NOT ECHO ANYTHING HERE!  This can interfere with JSON responses later.
} catch (mysqli_sql_exception $e) {
    // Log the error (recommended for production)
    error_log("Database Connection Error: " . $e->getMessage());

    // Properly handle the error for the user (or the application)
    die("Connection failed: " . $e->getMessage()); 
    // OR, if you want to redirect:
    // header("Location: error_page.php");  exit(); 
}
?>