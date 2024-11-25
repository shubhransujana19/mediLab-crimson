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
    $haemoglobin = $_POST['haemoglobin'];
    $bloodCellCount = $_POST['blood-cell-count'];
    $packedCellVolume = $_POST['packed-cell-volume'];
    $corpuscularVolume = $_POST['corpuscular-volume'];
    $corpuscularHaemoglobin = $_POST['corpuscular-haemoglobin'];
    $corpuscularHaemoglobinCoro = $_POST['corpuscular-haemoglobin-coro'];
    $plateletCount = $_POST['platelet-count'];    
    $whiteBloodCellCount = $_POST['white-blood-cell-count'];
    $dWhiteBloodCellCount = $_POST['d-white-blood-cell-count'];
    $neutrophils = $_POST['neutrophils'];
    $lymphocytes = $_POST['lymphocytes'];
    $monocytes = $_POST['monocytes'];
    $eosinophils = $_POST['eosinophils'];
    $basophils = $_POST['basophils'];
    $malariaParasites = $_POST['malaria-parasistes'];
    $abnormalCells = $_POST['abnormal-cells'];
    $reticulocyteCount = $_POST['reticulocyte-count'];
    $bloodGroupAboSystem = $_POST['blood-group-abo-system'];
    $rhFactor = $_POST['rh-factor'];
    $bleedingTimeMin = $_POST['bleeding-time-min'];
    $bleedingTimeSec = $_POST['bleeding-time-sec'];
    $coagulationTimeMin = $_POST['coagulation-time-min'];
    $coagulationTimeSec = $_POST['coagulation-time-sec'];
    $firstHr = $_POST['1st-hr'];
    $secondHr = $_POST['2nd-hr'];
    $mean = $_POST['mean'];

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
$sql = "INSERT INTO hemotology (patient_name, uhid, refBy, age, date, material, gender, haemoglobin, blood_cell_count, packed_cell_volume, corpuscular_volume, corpuscular_haemoglobin, corpuscular_haemoglobin_coro, platelet_count, white_blood_cell_count, d_white_blood_cell_count, neutrophils, lymphocytes, monocytes, eosinophils, basophils, malaria_parasites, abnormal_cells, reticulocyte_count, blood_group_abo_system, rh_factor, bleeding_time_min, bleeding_time_sec, coagulation_time_min, coagulation_time_sec, first_hr, second_hr, mean) 
VALUES ('$patientName', '$uhid', '$refBy', '$age', '$date', '$material', '$gender', '$haemoglobin', '$bloodCellCount', '$packedCellVolume', '$corpuscularVolume', '$corpuscularHaemoglobin', '$corpuscularHaemoglobinCoro', '$plateletCount', '$whiteBloodCellCount', '$dWhiteBloodCellCount', '$neutrophils', '$lymphocytes', '$monocytes', '$eosinophils', '$basophils', '$malariaParasites', '$abnormalCells', '$reticulocyteCount', '$bloodGroupAboSystem', '$rhFactor', '$bleedingTimeMin', '$bleedingTimeSec', '$coagulationTimeMin', '$coagulationTimeSec', '$firstHr', '$secondHr', '$mean')";
       
        if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        
            // Redirect to pathology.php and append the success message as a URL parameter
            $successMessage = urlencode($response['message']);
            header("Location: pathology.php?table=hemotology&success=$successMessage");
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?table=hemotology&error=$errorMessage");
            exit();
        }

}

// Close connection
$conn->close();
?>
