<?php
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// try {
//     $conn = new mysqli('localhost', 'serverwe_wmps', 'wmps@580', 'serverwe_pacs_linces');
//     $conn->set_charset('utf8mb4');
// } catch (Exception $e) {
//     error_log($e->getMessage());
// }

require_once 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all patient reports
$sql = "SELECT * FROM biochemical";
$result = $conn->query($sql);

$reports = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($reports);

// Close connection
$conn->close();
?>
