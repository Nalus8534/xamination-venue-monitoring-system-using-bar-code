<?php
// search_students.php
include 'db_connection.php'; // Include the database connection
header('Content-Type: text/html'); // Send HTML snippets

$output = '';
$row_number = 1; // Initialize row number for search results

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $search_term = "%" . $query . "%";

    // Modify SQL query to select all columns needed for the table
    $sql = "SELECT full_name, admission_number, nta_level, exam_number, program, venue
            FROM venues
            WHERE full_name LIKE ? OR admission_number LIKE ? OR exam_number LIKE ? OR program LIKE ? OR venue LIKE ?"; // Added program and venue to search
    $stmt = $conn->prepare($sql);
    // Bind parameters - need to match the number of placeholders
    $stmt->bind_param("sssss", $search_term, $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
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
        $output = "<tr><td colspan='7' style='text-align: center;'>No students found matching your query.</td></tr>";
    }
    $stmt->close();
} else {
    // If no query is provided, maybe return an empty state or all students?
    // Based on the JS, an empty query triggers fetchStudents(), so this else might not be strictly needed for the current JS logic,
    // but it's good practice for the PHP script itself.
    // Let's return an empty state if no query is provided directly to search_students.php
    $output = "<tr><td colspan='7' style='text-align: center;'>Please enter a search query.</td></tr>"; // Updated colspan
}

$conn->close();
echo $output;
?>
