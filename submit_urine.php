<?php
// Include the database connection file
require_once 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and sanitize inputs
    $patientName = mysqli_real_escape_string($conn, $_POST['patient-name']);
    $uhid = mysqli_real_escape_string($conn, $_POST['uhid']);
    $refBy = mysqli_real_escape_string($conn, $_POST['refBy']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $material = mysqli_real_escape_string($conn, $_POST['material']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $colour = mysqli_real_escape_string($conn, $_POST['colour']);
    $appearance = mysqli_real_escape_string($conn, $_POST['appearance']);
    $specificGravity = mysqli_real_escape_string($conn, $_POST['specific-gravity']);
    $deposits = mysqli_real_escape_string($conn, $_POST['deposits']);
    $pusCells = mysqli_real_escape_string($conn, $_POST['pus-cells']);
    $epithelialCells = mysqli_real_escape_string($conn, $_POST['epithelial-cells']);
    $rbc = mysqli_real_escape_string($conn, $_POST['rbc']);
    $crystals = mysqli_real_escape_string($conn, $_POST['crystals']);
    $yeastCells = mysqli_real_escape_string($conn, $_POST['yeast-cells']);
    $reaction = mysqli_real_escape_string($conn, $_POST['reaction']);
    $albumin = mysqli_real_escape_string($conn, $_POST['albumin']);
    $phosphate = mysqli_real_escape_string($conn, $_POST['phosphate']);
    $protein = mysqli_real_escape_string($conn, $_POST['protein']);
    $glucose = mysqli_real_escape_string($conn, $_POST['glucose']);
    $acetone = mysqli_real_escape_string($conn, $_POST['acetone']);
    $bileSalt = mysqli_real_escape_string($conn, $_POST['bile-salt']);
    $bilePigment = mysqli_real_escape_string($conn, $_POST['bile-pigment']);
    $occultBloodTest = mysqli_real_escape_string($conn, $_POST['occult-blood-test']);
    
      // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO urine (patient_name, uhid, ref_by, age, date, material, gender, quantity, colour, appearance, specific_gravity, deposits, pus_cells, epithelial_cells, rbc, crystals, yeast_cells, reaction, albumin, phosphate, protein, glucose, acetone, bile_salt, bile_pigment, occult_blood_test) 
            VALUES ('$patientName', '$uhid', '$refBy', '$age', '$date', '$material', '$gender', '$quantity', '$colour', '$appearance', '$specificGravity', '$deposits', '$pusCells', '$epithelialCells', '$rbc', '$crystals', '$yeastCells', '$reaction', '$albumin', '$phosphate', '$protein', '$glucose', '$acetone', '$bileSalt', '$bilePigment', '$occultBloodTest')";

            if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'New record created successfully';
        
            // Redirect to pathology.php and append the success message as a URL parameter
            $successMessage = urlencode($response['message']);
            header("Location: pathology.php?table=urine&success=$successMessage");
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
        
            // Redirect to pathology.php and append the error message as a URL parameter
            $errorMessage = urlencode($response['message']);
            header("Location: pathology.php?table=urine&error=$errorMessage");
            exit();
        }


    // Close connection
    $conn->close();
}
?>
