<?php
// Check if the price data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['price'])) {
    // Get the submitted prices
    $prices = $_POST['price'];

    // Loop through the submitted prices and update the database
    foreach ($prices as $test_type => $price) {
        $sql = "UPDATE test_prices SET price = '$price' WHERE test_type = '$test_type'";
        if ($conn->query($sql) === FALSE) {
            $response['status'] = 'error';
            $response['message'] = 'Error updating price for ' . $test_type . ': ' . $conn->error;
            
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?error=$errorMessage");
            exit();
        }
    }

    // Display a success message
    $response['status'] = 'success';
    $response['message'] = 'Prices updated successfully.';
    
    // Redirect to pathology.php and append the success message as a URL parameter
    $successMessage = urlencode($response['message']);
    header("Location: pathology.php?success=$successMessage");
    exit();
    
    }


// Close the database connection
$conn->close();