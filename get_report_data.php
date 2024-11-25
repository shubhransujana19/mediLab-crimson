<?php

require_once 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the request
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    // Sanitize the input
    $id = intval($_GET["id"]);

    // Fetch report data from the database based on the provided ID
    $sql = "SELECT * FROM biochemical WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convert the result into an associative array
        $reportData = $result->fetch_assoc();

        // Output the report data as JSON
        header('Content-Type: application/json');
        echo json_encode($reportData);
    } else {
        // No report found for the given ID
        http_response_code(404);
        echo json_encode(array("message" => "Report not found."));
    }
} else {
    // Invalid request method or missing parameters
    http_response_code(400);
    echo json_encode(array("message" => "Invalid request."));
}

// Close the database connection
$conn->close();
?>
