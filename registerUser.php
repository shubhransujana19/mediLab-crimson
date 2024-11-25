<?php
// Include the database connection file
require_once 'db.php';

// Initialize response array
$response = array();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate form data
    if (empty($username) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Both Username and Password are required.';
    } else {
        // Hash the password securely using bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        // Use a prepared statement to prevent SQL injection
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $username, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'User registered successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Database error: ' . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing statement: ' . $conn->error;
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Please use POST.';
}

// Close the database connection
$conn->close();

// Return the response in JSON format
header('Content-Type: application/json');
echo json_encode($response);

header('location: login.php');
?>