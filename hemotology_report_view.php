<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reports</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    
 <style>
       body {
            font-family: verdana, Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 10px;
            padding: 1px;
            font-size:12px;
        }
        table tr td table tr th{
          border-style: 1pt solid black;
          border: 1pt;
          text-align: left;
          padding:5px;
        }
        table tr td table tr td{
           border-style: 1px solid black;
           border: 1pt;
           /*border-left-style:solid;*/
           text-align: left;
           padding:5px;
           font-weight:500;
        }
      
        #myTable th:nth-child(2),
        #myTable th:nth-child(3),
        #myTable th:nth-child(4),
        #myTable th:nth-child(5),
        #myTable th:nth-child(6) {
            border-left: 1pt solid black; /* Adjust the style as needed */
            text-align:center;
        }


        #myTable td:nth-child(2),
        #myTable td:nth-child(3)
       {
            border-left: 1pt solid black; /* Adjust the style as needed */
            text-align:center;
        }

        h2 {
            margin:22px;
            text-align: center;
            color: #333;
        }
        .unique-heading {
          background-image: linear-gradient(to right, #337ab7, #00c292); /* Adjust colors as needed */
          color: #fff; /* Text color for the heading */
          padding: 15px 20px;
          border-radius: 5px;
          text-align: center;
          font-size: 18px;
          margin-bottom: 15px;
        }

        #patientReports {
            margin-top: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            overflow: hidden;
        }
        

        @media screen{
          body{
            font-family: verdana;
          }
          

        .actions {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #337ab7;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #286090;
        }

        .btn i {
            margin-right: 5px;
        }
        
    }
    
    @media print {
      @page {
        size: A4 portrait;
        margin: 5cm 1cm 3cm 1cm ; /* 2cm top and bottom, 1cm left and right */
      }
    
    .printBtn {
        display: none;
      }
     .actions {
            display: none;
        }      
      
    }
    



     </style>
    
    </head>
<body>
 
  
    <h2><span class="unique-heading">HAEMATOLOGY</span></h2>

    <div id="patientReports">
        <?php
        // Database connection
        require_once 'db.php';

        // Check if the patient ID is provided
        if(isset($_GET['id'])) {
            // Fetch report data from the database based on the provided patient ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM hemotology WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Fetch the report details
                $row = $result->fetch_assoc();
                $patientName = $row['patient_name'];
                $uhid = $row['uhid'];
                $date = $row['date'];
                // You can fetch other report details here

                // Generate the report HTML
                ?>
        <table width="100%" Border="1" id="myTable">
        <thead>
            <tr>
                <td width="100%">
                    <table>
                        <tr >
                        <th width="15%" >Patient Name</th>
                        <th width="35%"><?php echo $row['patient_name'] ?></th>
                        <th width="10%">SEX</th>
                        <th width="15%"><?php echo $row['gender'] ?></th>
                        <th width="10%">AGE</th>
                        <th width="15%"><?php echo $row['age'] ?> YRS.</th>
                        </tr>
                    </table>  
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                        <th width="15%">REF. BY</th>
                        <th width="35%"><?php echo $row['refBy'] ?></th>
                        <th width="10%">UHID</th>
                        <th width="40%"><?php echo $row['uhid'] ?></th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                        <th width="15%">MATERIAL</th>
                        <th width="35%"><?php echo $row['material'] ?></th>
                        <th width="10%">DATE</th>
                        <th width="40%"><?php echo $row['date'] ?></th> 
                        </tr>
                    </table>
                </td>
            </tr>
        </p>
        </thead> 

        <tbody>
            <tr>
            <td>
                <table>
                    <tr style="border-bottom: 1px solid black;">
                        <th width="33.33%" align="center">INVESTIGATION</th>
                        <th width="33.33%" align="center">FINDINGS</th>
                        <th width="33.33%" align="center">NORMAL RANGE</th>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">HAEMOGLOBIN</td>
                        <td width="33.33%" align="center"> <?php echo $row['haemoglobin'] ?></td>
                        <td width="33.33%" align="center"><span>M: 14 - 16 / F: 12 - 16</span> gm/dl</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">BLOOD CELL COUNT</td>
                        <td width="33.33%" align="center"><?php echo $row['blood_cell_count'] ?></td>
                        <td width="33.33%" align="center"><span>M: 4.6 - 6 / F: 3.7 - 5</span> mill/cu mm</td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">PACKED CELL VOLUME</td>
                        <td width="33.33%" align="center"><?php echo $row['packed_cell_volume'] ?></td>
                        <td width="33.33%" align="center"><span>M: 40.0 - 50.0 / F: 37.0 - 47.0 </span> %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">MEAN CORPUSCULAR VOLUME</td>
                        <td width="33.33%" align="center"><?php echo $row['corpuscular_volume'] ?></td>
                        <td width="33.33%" align="center">76 - 95 fl</td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">MEAN CORPUSCULAR HAEMOGLOBIN </td>
                        <td width="33.33%" align="center"><?php echo $row['corpuscular_haemoglobin'] ?></td>
                        <td width="33.33%" align="center">27 - 32 pg</td>
                    </tr>

                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">MEAN CORPUSCULAR HAEMOGLOBIN CORO</td>
                        <td width="33.33%" align="center"><?php echo $row['corpuscular_haemoglobin_coro'] ?></td>
                        <td width="33.33%" align="center">30 - 35 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">PLATELET COUNT</td>
                        <td width="33.33%" align="center"><?php echo $row['platelet_count'] ?></td>
                        <td width="33.33%" align="center">150.0 - 400.0 / cu mm</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">TOTAL WHITE BLOOD CELL COUNT</td>
                        <td width="33.33%" align="center"><?php echo $row['white_blood_cell_count'] ?></td>
                        <td width="33.33%" align="center">4000 - 10000 / cu mm</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">DIFFERENTIAL BLOOD CELL COUNT</td>
                        <td width="33.33%" align="center"><?php echo $row['d_white_blood_cell_count'] ?></td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">NEUTROPHILS</td>
                        <td width="33.33%" align="center"><?php echo $row['neutrophils'] ?></td>
                        <td width="33.33%" align="center">40 - 70 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">LYMPHOCYTES</td>
                        <td width="33.33%" align="center"><?php echo $row['lymphocytes'] ?></td>
                        <td width="33.33%" align="center">20 - 45 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">MONOCYTES</td>
                        <td width="33.33%" align="center"><?php echo $row['monocytes'] ?></td>
                        <td width="33.33%" align="center">1 - 10 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">EOSINOPHILS</td>
                        <td width="33.33%" align="center"><?php echo $row['eosinophils'] ?></td>
                        <td width="33.33%" align="center">1 - 6 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">BASOPHILS</td>
                        <td width="33.33%" align="center"><?php echo $row['basophils'] ?></td>
                        <td width="33.33%" align="center">0 - 0.5 %</td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">MALARIA PARASITES</td>
                        <td width="33.33%" align="center"><?php echo $row['malaria_parasites'] ?></td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">ABNORMAL CELLS</td>
                        <td width="33.33%" align="center"><?php echo $row['abnormal_cells'] ?></td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">RETICULOCYTE COUNT</td>
                        <td width="33.33%" align="center"><?php echo $row['reticulocyte_count'] ?></td>
                        <td width="33.33%" align="center">0.5 - 1 %</td>
                    </tr>
                   <tr>
                        <td width="33.33%" align="center">BLOOD GROUP (ABO SYSTEM)</td>
                        <td width="33.33%" align="center"><?php echo $row['blood_group_abo_system'] ?></td>
                        <td width="33.33%" align="center"></td>
                    </tr>

            </table>
            </td>
            </tr>
            
            <tr>
                <td>
                    <table>
                      <tr>
                        <td rowspan="3" width="33.33%" style="border-right: 1px solid black; ">ESR (WESTERGREEN METHOD)</td>
                        <td style="border-bottom: 1px solid black; padding: 5px; >
                          <span style="text-align:left;" >1ST HR. &nbsp;</span> <?php echo $row['first_hr']; ?> MM
                        </td>
                        <td rowspan="3" width="33.33%" ><span> 1ST HR. <br> M: 2 - 7 <br> F: 2 - 10</span> </td>
                      </tr>
                      <tr >
                        <td style="border-bottom: 1px solid black; padding: 5px; text-align: center;">
                          <span>2ND HR. &nbsp;</span> <?php echo $row['second_hr']; ?> MM
                        </td>
                      </tr>
                      <tr >
                        <td style=" padding: 5px; text-align: center; ">
                          <span>MEAN &nbsp;</span> <?php echo $row['mean']; ?> MM
                        </td>
                      </tr>
                    </table>
                </td>
            </tr>
            <tr>
            <td>
            <table>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">RH (FACTOR)</td>
                        <td width="33.33%" align="center"><?php echo $row['rh_factor'] ?></td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                   <tr style="border-bottom: 1px solid black;">
                        <td width="33.33%" align="center">BLEEDING TIME</td>
                        <td width="33.33%" align="center">
                        <span> &nbsp;<?php echo $row['bleeding_time_min'] ?>&nbsp; MINS</span> 
                        <span> &nbsp;<?php echo $row['bleeding_time_sec'] ?>&nbsp; SEC.</span> 
                        </td> </td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                   <tr>
                        <td width="33.33%" align="center">COAGULATION TIME</td>
                        <td width="33.33%" align="center">
                        <span> &nbsp;<?php echo $row['coagulation_time_min'] ?>&nbsp; MINS</span> 
                        <span> &nbsp;<?php echo $row['coagulation_time_sec'] ?>&nbsp; SEC.</span> 
                        </td> </td>
                        <td width="33.33%" align="center"></td>
                    </tr>
                    
            </table>
            </td>
            </tr>
            
                        
        </tbody>

        </table>
        
        <?php
            } else {
                echo "Report not found";
            }
        } else {
            echo "Patient ID not provided";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
    
            <p>&nbsp;</p>
            
        <div class="actions">
            <a href="#" class="btn" onclick="printReport()"><i class="fas fa-print"></i> Print Report</a>
            <a href="#" class="btn"><i class="fas fa-file-pdf"></i> Download PDF</a>
        </div>
        
     <script>
        function printReport() {
            var printContents = document.getElementById("patientReports").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
        


    </script>
</body>
</html>
