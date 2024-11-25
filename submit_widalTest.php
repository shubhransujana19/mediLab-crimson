<?php

require_once 'db.php';

$response = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patientName = $_POST['patient-name'];
    $uhid = $_POST['uhid'];
    $refBy = $_POST['refBy'];
    $age = $_POST['age'];
    $date = $_POST['date'];
    $material = $_POST['material'];
    $gender = $_POST['gender'];
    $glucoseF = $_POST['glucoseF'];
    $glucose2hr = $_POST['glucose2hr'];
    $glucoseRandom = $_POST['glucoseRandom'];
    $uricAcid = $_POST['uric-acid'];
    $details = $_POST['details'];



    // Check connection
    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Connection failed: ' . $conn->connect_error;
    } else {
        // SQL query to insert data into the database
        $sql = "INSERT INTO widalTest (patient_name, uhid, ref_by, age, date, material, gender, glucose_f, glucose_2hr, glucose_random, uric_acid, details)
        VALUES ('$patientName', '$uhid', '$refBy', '$age', '$date', '$material', '$gender', '$glucoseF', '$glucose2hr', '$glucoseRandom', '$uricAcid', '$details')";

            if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        
            // Redirect to pathology.php and append the success message as a URL parameter
            $successMessage = urlencode($response['message']);
            header("Location: pathology.php?table=widalTest&success=$successMessage");
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?table=widalTest&error=$errorMessage");
            exit();
        }


        // Close connection
        $conn->close();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Form not submitted';
}

// Output JSON response
    header('location: pathology.php?table=widalTest');
    exit;
echo json_encode($response);
?>
