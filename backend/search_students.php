<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\search_students.php -->
<?php
include 'db_connection.php'; // Include the database connection

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $search_term = "%" . $query . "%";

    $sql = "SELECT full_name, admission_number, nta_level, exam_number, program, venue 
            FROM venues 
            WHERE full_name LIKE ? OR admission_number LIKE ? OR exam_number LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['admission_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nta_level']) . "</td>";
            echo "<td>" . htmlspecialchars($row['exam_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['program']) . "</td>";
            echo "<td>" . htmlspecialchars($row['venue']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align: center;'>No data available</td></tr>";
    }
}
?>