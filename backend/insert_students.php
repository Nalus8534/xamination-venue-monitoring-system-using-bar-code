<?php
include 'db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Improved error logging
function log_error($message)
{
    $log_file = 'error_log.txt'; // Use a more specific log file
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $log_file); // Append to the log file
}

if (!is_array($data) || empty($data)) {
    $error_message = "Invalid or empty data received.";
    log_error($error_message);
    echo json_encode(["status" => "error", "message" => $error_message]);
    exit();
}

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO venues (full_name, admission_number, nta_level, exam_number, program, venue) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $error_message = "Failed to prepare statement: " . $conn->error;
        log_error($error_message);
        echo json_encode(["status" => "error", "message" => $error_message]);
        exit();
    }

    foreach ($data as $student) {
        // Validate student data before insertion
        if (
            !isset($student['full_name']) || empty($student['full_name']) ||
            !isset($student['admission_number']) || empty($student['admission_number']) ||
            !isset($student['nta_level']) || empty($student['nta_level']) ||
            !isset($student['venue']) || empty($student['venue'])
        ) {
            $error_message = "Missing required data for a student: " . json_encode($student);
            log_error($error_message);
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => $error_message]);
            exit();
        }

        $stmt->bind_param(
            "ssssss",
            $student['full_name'],
            $student['admission_number'],
            $student['nta_level'],
            $student['exam_number'],  //these can be empty
            $student['program'],      //these can be empty
            $student['venue']
        );

        if (!$stmt->execute()) {
            $error_message = "Execute failed: " . $stmt->error . " for student: " . json_encode($student);
            log_error($error_message);
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => "Failed to insert data: " . $stmt->error]);
            exit();
        }
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Data inserted successfully."]);
} catch (Exception $e) {
    $conn->rollback();
    $error_message = "Exception: " . $e->getMessage();
    log_error($error_message);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
} finally {
    if ($stmt) {
        $stmt->close();
    }
    $conn->close();
}
?>
