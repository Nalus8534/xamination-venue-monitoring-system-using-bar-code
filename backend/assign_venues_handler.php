<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\assign_venues_handler.php -->
<?php
include 'session_check.php';
include 'db_connection.php'; // Include the database connection

$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $admission_number = $_POST['admission_number'];
    $nta_level = $_POST['nta_level'];
    $exam_number = $_POST['exam_number'];
    $program = $_POST['program'];
    $venue = $_POST['venue'];

    try {
        // Check if the student already exists in the venues table
        $check_sql = "SELECT * FROM venues WHERE full_name = ? OR admission_number = ? OR exam_number = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("sss", $full_name, $admission_number, $exam_number);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Check which field is causing the duplication
            $duplicate_fields = [];
            while ($row = $result->fetch_assoc()) {
                if ($row['full_name'] === $full_name) {
                    $duplicate_fields[] = "Full Name";
                }
                if ($row['admission_number'] === $admission_number) {
                    $duplicate_fields[] = "Admission Number";
                }
                if ($row['exam_number'] === $exam_number) {
                    $duplicate_fields[] = "Exam Number";
                }
            }

            // Create a detailed error message
            $message = "<div style='color: red; margin-top: 10px;'>Error: Duplicate entry found for " . implode(", ", $duplicate_fields) . ".</div>";
        } else {
            // Insert data into the venues table
            $sql = "INSERT INTO venues (full_name, admission_number, nta_level, program, exam_number, venue) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $full_name, $admission_number, $nta_level, $program, $exam_number, $venue);

            if ($stmt->execute()) {
                $message = "<div style='color: green; margin-top: 10px;'>Student assigned to venue successfully!</div>";
            } else {
                $message = "<div style='color: red; margin-top: 10px;'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }

        $check_stmt->close();
    } catch (Exception $e) {
        $message = "<div style='color: red; margin-top: 10px;'>An error occurred: " . $e->getMessage() . "</div>";
    }

    // Close the database connection
    $conn->close();
}
?>