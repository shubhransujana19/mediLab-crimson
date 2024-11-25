<?php

require_once 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $patientName = $_POST['patient-name'];
    $uhid = $_POST['uhid'];
    $refBy = $_POST['refBy'];
    $age = $_POST['age'];
    $date = $_POST['date'];
    $material = $_POST['material'];
    $gender = $_POST['gender'];
    $glucoseF = $_POST['glucoseF'];
    $glucose2hrPP = $_POST['glucose2hrPP'];
    $glucoseRandom = $_POST['glucoseRandom'];
    $urea = $_POST['urea'];
    $bun = $_POST['bun'];
    $creatinine = $_POST['creatinine'];
    $uricAcid =$_POST['uric-acid'];
    $bilirubinT = $_POST['bilirubinT'];
    $bilirubinC = $_POST['bilirubinC'];
    $bilirubinUC = $_POST['bilirubinUC'];
    $proteint = $_POST['proteint'];
    $albumin = $_POST['albumin'];
    $globulin = $_POST['globulin'];
    $astSgot = $_POST['ast-sgot'];
    $altSgpt = $_POST['alt-sgpt'];
    $alkPhosphatase = $_POST['alk-phosphatase'];
    $cholesterol = $_POST['cholesterol'];
    $vldlCholesterol = $_POST['vldl-cholesterol'];
    $hdlCholesterol = $_POST['hdl-cholesterol'];
    $ldlCholesterol = $_POST['ldl-cholesterol'];
    $triglyCerides = $_POST['trigly-cerides'];
    $cpkMb = $_POST['cpk-mb'];
    $sodium = $_POST ['sodium'];
    $potassium = $_POST['potassium'];
    $chloride = $_POST['chloride'];
    $calcium = $_POST['calcium'];

    // Sanitize and validate form data (example only, improve as needed)
    $patientName = mysqli_real_escape_string($conn, $patientName);
    $uhid = mysqli_real_escape_string($conn, $uhid);
    $refBy = mysqli_real_escape_string($conn, $refBy);
    $age = mysqli_real_escape_string($conn, $age);
    $date = mysqli_real_escape_string($conn, $date);
    $material = mysqli_real_escape_string($conn, $material);
    $gender = mysqli_real_escape_string($conn, $gender);
    // Sanitize and validate other form fields similarly...

    // Insert data into database
    $sql = "INSERT INTO biochemical (patient_name, uhid, refBy, age, date, material, gender, glucoseF, glucose2hrPP, glucoseRandom, urea, bun, creatinine, uricAcid, bilirubinT, bilirubinC, bilirubinUC, proteint, albumin, globulin, astSgot,altSgpt, alkPhosphatase, cholesterol,hdlCholesterol, vldlCholesterol, ldlCholesterol, triglyCerides, cpkMb, sodium, potassium, chloride, calcium) 
    VALUES ('$patientName', '$uhid', '$refBy', '$age', '$date', '$material', '$gender', '$glucoseF', '$glucose2hrPP', '$glucoseRandom', '$urea', '$bun', '$creatinine', '$uricAcid', '$bilirubinT', '$bilirubinC', '$bilirubinUC', '$proteint', '$albumin', '$globulin', '$astSgot','$altSgpt', '$alkPhosphatase', '$cholesterol','$hdlCholesterol', '$vldlCholesterol', '$ldlCholesterol', '$triglyCerides', '$cpkMb', '$sodium', '$potassium', '$chloride', '$calcium')";

            if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        
            // Redirect to pathology.php and append the success message as a URL parameter
            $successMessage = urlencode($response['message']);
            header("Location: pathology.php?table=biochemical&success=$successMessage");
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?table=biochemical&error=$errorMessage");
            exit();
        }

}

// Close connection
$conn->close();
?>
