<?php
// fetch_all_students.php
include 'db_connection.php'; // Include the database connection
header('Content-Type: text/html'); // Send HTML snippets

$output = '';
$row_number = 1; // Initialize row number

try {
    $sql = "SELECT full_name, admission_number, nta_level, exam_number, program, venue FROM venues ORDER BY full_name";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= "<tr>";
            $output .= "<td>" . $row_number++ . "</td>"; // Add ID column
            $output .= "<td>" . htmlspecialchars($row['full_name'] ?? '') . "</td>";
            $output .= "<td>" . htmlspecialchars($row['admission_number'] ?? '') . "</td>";
            $output .= "<td>" . htmlspecialchars($row['nta_level'] ?? '') . "</td>";
            $output .= "<td>" . htmlspecialchars($row['exam_number'] ?? '') . "</td>";
            $output .= "<td>" . htmlspecialchars($row['program'] ?? '') . "</td>";
            $output .= "<td>" . htmlspecialchars($row['venue'] ?? '') . "</td>";
            $output .= "</tr>";
        }
    } else {
        // Update colspan to 7
        $output = "<tr><td colspan='7' style='text-align: center;'>No students found in the database.</td></tr>";
    }
} catch (Exception $e) {
    error_log("Database Error in fetch_all_students.php: " . $e->getMessage());
    // Update colspan to 7
    $output = "<tr><td colspan='7' style='text-align: center;'>Error fetching data.</td></tr>";
}
$conn->close();
echo $output;
?>
