<?php
// search_student_by_id.php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// Set header to return JSON
header('Content-Type: application/json');

// Check database connection
if (!$conn) {
    $response = ['found' => false, 'error' => 'Database connection failed.'];
    echo json_encode($response);
    exit;
}

// Sanitize input
$venue = htmlspecialchars($_GET['venue'] ?? '');
$admission_number = htmlspecialchars($_GET['admission_number'] ?? '');

// Validate input
if (empty($venue) || empty($admission_number)) {
    $response = ['found' => false, 'error' => 'Venue and admission number are required.'];
    echo json_encode($response);
    exit;
}

$response = ['found' => false];

// Prepare and execute SQL query
// *** MODIFIED: Added image_path to the SELECT statement ***
$stmt = $conn->prepare("SELECT full_name, admission_number, nta_level, exam_number, program, venue, image_path FROM venues WHERE venue = ? AND admission_number = ?");
if (!$stmt) {
    // Log the error for debugging on the server side
    error_log("Search Student SQL Prepare Error: " . $conn->error);
    $response = ['found' => false, 'error' => 'Failed to prepare SQL statement.'];
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ss", $venue, $admission_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    // Include all fetched details in the response, including image_path
    $response = [
        'found' => true,
        'full_name' => $student['full_name'],
        'admission_number' => $student['admission_number'],
        'nta_level' => $student['nta_level'],
        'exam_number' => $student['exam_number'],
        'program' => $student['program'],
        'venue' => $student['venue'],
        'image_path' => $student['image_path'] // *** ADDED: Include image_path ***
    ];
} else {
    // If no rows found, explicitly set found to false and provide a specific message
    $response = ['found' => false, 'message' => 'Student with Admission Number "' . htmlspecialchars($admission_number) . '" not found in Venue "' . htmlspecialchars($venue) . '".'];
}

// Close statement and connection
$stmt->close();
$conn->close();

echo json_encode($response);
?>
