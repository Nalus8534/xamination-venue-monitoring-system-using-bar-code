<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\db_connection.php -->
<?php
$servername = "localhost"; // Replace with your database server
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "examination_venue"; // Replace with your database name

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    echo "Connected successfully!";
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>