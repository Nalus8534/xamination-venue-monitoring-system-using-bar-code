<?php
// check_image.php
include 'db_connection.php'; // Include database connection

header('Content-Type: application/json'); // Set header to return JSON

// Check database connection
if (!$conn) {
    echo json_encode(['exists' => false, 'error' => 'Database connection failed.']);
    exit;
}

// Get the admission number from the request
$admission_number = $_GET['admission_number'] ?? '';

// Validate input
if (empty($admission_number)) {
    echo json_encode(['exists' => false, 'error' => 'Admission number is required.']);
    exit;
}

// Prepare and execute SQL query to check for existing image_path
// We check if image_path is NOT NULL and not an empty string
$stmt = $conn->prepare("SELECT image_path FROM venues WHERE admission_number = ? AND image_path IS NOT NULL AND image_path != '' LIMIT 1");
if (!$stmt) {
    error_log("Check Image SQL Prepare Error: " . $conn->error);
    echo json_encode(['exists' => false, 'error' => 'Failed to prepare SQL statement.']);
    exit;
}

$stmt->bind_param("s", $admission_number);
$stmt->execute();
$result = $stmt->get_result();

$image_exists = $result->num_rows > 0;
$existing_path = null;
if ($image_exists) {
    $row = $result->fetch_assoc();
    $existing_path = $row['image_path'];
}

$stmt->close();
$conn->close();

echo json_encode(['exists' => $image_exists, 'image_path' => $existing_path]);
?>
