<?php
// Database connection
require_once 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if search parameter is provided
if(isset($_GET['search'])) {
    // Sanitize the input to prevent SQL injection
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    // Construct SQL query to search for matching records
    $sql = "SELECT * FROM biochemical WHERE patient_name LIKE '%$search%' OR uhid LIKE '%$search%'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo '<table>';
        echo '<tr>';
        echo '<th><i class="fas fa-sort"></i> Name</th>'; 
        echo '<th><i class="fas fa-sort"></i> UHID</th>'; 
        echo '<th><i class="fas fa-sort"></i> Date</th>'; 
        echo '<th><i class="fas fa-file-medical"></i> View Report</th>';
        echo '<th><i class="fas fa-file-alt"></i> View Bill</th>';
        echo '<th><i class="fas fa-edit"></i> Edit Report</th>';
        echo '<th><i class="fas fa-trash"></i> Delete Report</th>';
        echo '</tr>';
      
        while ($row = $result->fetch_assoc()) {
          echo '<tr>';
          echo '<td style="text-align: center;">' . $row['patient_name'] . '</td>';
          echo '<td style="text-align: center;">' . $row['uhid'] . '</td>';
          echo '<td style="text-align: center;">' . $row['date'] . '</td>';
          echo '<td class="icon" onclick="viewReport(' . $row['id'] . ')"></i> <i class="fas fa-eye" style="font-size: 12px; position: relative; top: -2px;"></i>&nbsp; View Report</td>';
          echo '<td class="icon" onclick="viewBill(' . $row['id'] . ')"><i class="fas fa-file-invoice-dollar"></i>&nbsp; View Bill</td>';
          echo '<td class="icon" onclick="editReport(' . $row['id'] . ')"><i class="fas fa-edit"></i>&nbsp; Edit Report</td>';
          echo '<td class="icon" onclick="deleteBill(' . $row['id'] . ')"><i class="fas fa-trash"></i>&nbsp; Delete Report</td>';
      
          echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "0 results";
    }
} else {
    // Default query when search parameter is not provided
    $sql = "SELECT * FROM biochemical";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo '<table>';
        echo '<tr>';
        echo '<th><i class="fas fa-sort"></i> Name</th>'; 
        echo '<th><i class="fas fa-sort"></i> UHID</th>'; 
        echo '<th><i class="fas fa-sort"></i> Date</th>'; 
        echo '<th><i class="fas fa-file-medical"></i> View Report</th>';
        echo '<th><i class="fas fa-file-alt"></i> View Bill</th>';
        echo '<th><i class="fas fa-edit"></i> Edit Report</th>';
        echo '<th><i class="fas fa-trash"></i> Delete Report</th>';
        echo '</tr>';
      
        while ($row = $result->fetch_assoc()) {
          echo '<tr>';
          echo '<td style="text-align: center;">' . $row['patient_name'] . '</td>';
          echo '<td style="text-align: center;">' . $row['uhid'] . '</td>';
          echo '<td style="text-align: center;">' . $row['date'] . '</td>';
          echo '<td class="icon" onclick="viewReport(' . $row['id'] . ')"></i> <i class="fas fa-eye" style="font-size: 12px; position: relative; top: -2px;"></i>&nbsp; View Report</td>';
          echo '<td class="icon" onclick="viewBill(' . $row['id'] . ')"><i class="fas fa-file-invoice-dollar"></i>&nbsp; View Bill</td>';
          echo '<td class="icon" onclick="editReport(' . $row['id'] . ')"><i class="fas fa-edit"></i>&nbsp; Edit Report</td>';
          echo '<td class="icon" onclick="deleteBill(' . $row['id'] . ')"><i class="fas fa-trash"></i>&nbsp; Delete Report</td>';
      
          echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "0 results";
    }
}

$conn->close();
?>
