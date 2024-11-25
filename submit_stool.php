<?php

require_once 'db.php'; // Assuming 'db.php' contains your database connection

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
    $colour = $_POST['colour'];
    $consistency = $_POST['consistency'];
    $mucus = $_POST['mucus'];
    $frankBlood = $_POST['frank-blood'];
    $reducingSubstances = $_POST['reducing-substances'];
    $reaction = $_POST['reaction'];
    $occultBloodTest = $_POST['occult-blood-test'];
    $ph = $_POST['ph'];
    $ova = $_POST['ova'];
    $cyst = $_POST['cyst'];
    $protozoa = $_POST['protozoa'];
    $vegetableCell = $_POST['vegetable-cell'];
    $pusCell = $_POST['pus-cell'];
    $rbc = $_POST['rbc'];
    $microOrganism = $_POST['micro-organism'];
    $starch = $_POST['starch'];
    $fat = $_POST['fat'];
    $others = $_POST['others'];

    // Check connection
    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Connection failed: ' . $conn->connect_error;
    } else {
        // SQL query to insert data into the database
        $sql = "INSERT INTO stool (patient_name, uhid, ref_by, age, date, material, gender, colour, consistency, mucus, frank_blood, reducing_substances, reaction, occult_blood_test, ph, ova, cyst, protozoa, vegetable_cell, pus_cell, rbc, micro_organism, starch, fat, others)
        VALUES ('$patientName', '$uhid', '$refBy', '$age', '$date', '$material', '$gender', '$colour', '$consistency', '$mucus', '$frankBlood', '$reducingSubstances', '$reaction', '$occultBloodTest', '$ph', '$ova', '$cyst', '$protozoa', '$vegetableCell', '$pusCell', '$rbc', '$microOrganism', '$starch', '$fat', '$others')";
       
        if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        
            // Redirect to pathology.php and append the success message as a URL parameter
            $successMessage = urlencode($response['message']);
            header("Location: pathology.php?table=stool&success=$successMessage");
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?table=stool&error=$errorMessage");
            exit();
        }

        // Close connection
        $conn->close();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Form not submitted';
}

 header('Location: pathology.php?table=stool');
            exit;
echo json_encode($response);
?>
