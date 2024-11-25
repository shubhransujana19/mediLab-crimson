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
           border-style: 1pt solid black;
           border: 1pt;
           /*border-left-style:solid;*/
           text-align: left;
           padding:5px;
        }
      
        #myTable th:nth-child(2),
        #myTable th:nth-child(3),
        #myTable th:nth-child(4),
        #myTable th:nth-child(5),
        #myTable th:nth-child(6) {
            border-left: 1px solid black; /* Adjust the style as needed */
        }


        #myTable td:nth-child(2),
        #myTable td:nth-child(3),
        #myTable td:nth-child(4),
        #myTable td:nth-child(5),
        #myTable td:nth-child(6){
            border-left: 1px solid black; /* Adjust the style as needed */
        }

        .header {
            background-color: #337ab7;
            color: #fff;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 500;
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
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
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
        margin: 5cm 1cm; /* 2cm top and bottom, 1cm left and right */
      }
    
     .actions {
                display: none;
            }
            
        
    }
    




     </style>
    
    </head>
<body>
<div class="header">
            <h1>Patient Reports</h1>
        </div>
        
    <h2><span class="unique-heading">BIOCHEMICAL</span></h2>

    <div id="patientReports">
        <?php
        // Database connection
        require_once 'db.php';

        // Check if the patient ID is provided
        if(isset($_GET['id'])) {
            // Fetch report data from the database based on the provided patient ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM biochemical WHERE id = $id";
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
            <tr width="100%">
                <td>
                    <table width="100%">
                        <tr>
                        <th width="20%" align="center">TEST PERFORMED</th>
                        <th width="15%" align="center">NORMAL</th>
                        <th width="10%" align="center">RESULT</th>
                        <th width="20%" align="center">TEST PERFORMED</th>
                        <th width="15%" align="center">NORMAL</th>
                        <th width="10%" align="center">RESULT</th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">GLUCOSE F</td>
                        <td width="15%" align="center">60 - 120 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['glucoseF'] ?></td>
                        <td width="20%" align="center">AST/SGOT</td>
                        <td width="15%" align="center">0 - 40 iu/l</td>
                        <td width="10%" align="center"><?php echo $row['astSgot'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
                       <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">GLUCOSE 2HR PP </td>
                        <td width="15%" align="center">70 - 140 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['glucose2hrPP'] ?></td>
                        <td width="20%" align="center">ALT/SGPT</td>
                        <td width="15%" align="center">0 - 40 iu/l</td>
                        <td width="10%" align="center"><?php echo $row['altSgpt'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">GLUCOSE RANDOM</td>
                        <td width="15%" align="center">60 - 140 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['glucoseRandom'] ?></td>
                        <td width="20%" align="center">ALK PHOSPHATASE</td>
                        <td width="15%" align="center">44 - 132 iu/l</td>
                        <td width="10%" align="center"><?php echo $row['alkPhosphatase'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">UREA</td>
                        <td width="15%" align="center">15 - 40 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['urea'] ?></td>
                        <td width="20%" align="center">CHOLESTEROL</td>
                        <td width="15%" align="center">150 - 200 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['cholesterol'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">BUN</td>
                        <td width="15%" align="center">7 - 19 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['bun'] ?></td>
                        <td width="20%" align="center">HDL CHOLESTEROL</td>
                        <td width="15%" align="center">40 - 80 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['hdlCholesterol'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">CREATININE</td>
                        <td width="15%" align="center"><span>0.9-1.4 mg/dl(M) <br> 0.6-1.2 mg/dl(F)</span></td>
                        <td width="10%" align="center"><?php echo $row['creatinine'] ?></td>

                        <td width="20%" align="center">VLDL CHOLESTEROL</td>
                        <td width="15%" align="center">18 - 20 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['vldlCholesterol'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">URIC ACID</td>
                        <td width="15%" align="center"><span>3.2-7.4 mg/dl(M) <br> 2.6-6.0 mg/dl(F)</span></td>
                        <td width="10%" align="center"><?php echo $row['uricAcid'] ?></td>
                        <td width="20%" align="center">LDL CHOLESTEROL</td>
                        <td width="15%" align="center">110 - 165 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['ldlCholesterol'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">BILIRUBIN T</td>
                        <td width="15%" align="center">0.2 - 1.0 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['bilirubinT'] ?></td>
                        <td width="20%" align="center">TRIGLY CERIDES</td>
                        <td width="15%" align="center">65 - 185 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['triglyCerides'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">BILIRUBIN C</td>
                        <td width="15%" align="center">0.0 - 0.5 mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['bilirubinC'] ?></td>
                        <td width="20%" align="center">C P K MB</td>
                        <td width="15%" align="center">Up to 24 u/l</td>
                        <td width="10%" align="center"><?php echo $row['cpkMb'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">BILIRUBIN UC</td>
                        <td width="15%" align="center"> mg/dl</td>
                        <td width="10%" align="center"><?php echo $row['bilirubinUC'] ?></td>
                        <td width="20%" align="center">SODIUM</td>
                        <td width="15%" align="center">135 - 155 mmol/L</td>
                        <td width="10%" align="center"><?php echo $row['sodium'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">PROTEINT</td>
                        <td width="15%" align="center">6.4 - 7.8 g/dl</td>
                        <td width="10%" align="center"><?php echo $row['proteint'] ?></td>
                        <td width="20%" align="center">POTASSIUM</td>
                        <td width="15%" align="center">3.5 - 5.5 mmol/L</td>
                        <td width="10%" align="center"><?php echo $row['potassium'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr>
                        <td width="20%" align="center">ALBUMIN</td>
                        <td width="15%" align="center">3.5 - 5.2 g/dl</td>
                        <td width="10%" align="center"><?php echo $row['albumin'] ?></td>
                        <td width="20%" align="center">CHLORIDE</td>
                        <td width="15%" align="center">&nbsp;</td>
                        <td width="10%" align="center"><?php echo $row['chloride'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr width="100%">
                <td>
                    <table width="100%" style="border-bottom-style:none;">
                        <tr >
                        <td width="20%" align="center">GLOBUMIN</td>
                        <td width="15%" align="center">&nbsp;</td>
                        <td width="10%" align="center"><?php echo $row['globulin'] ?></td>
                        <td width="20%" align="center">CALCIUM</td>
                        <td width="15%" align="center">&nbsp;</td>
                        <td width="10%" align="center"><?php echo $row['calcium'] ?></td>
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
