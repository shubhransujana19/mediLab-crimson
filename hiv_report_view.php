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
     /* Center the textarea horizontally */
        #textarea-container {
            margin: 0 auto;
            width: 100%; /* Adjust width as needed */
        }
    
        #textarea-container textarea {
            width: 100%;
            height: 400px; /* Adjust height as needed */
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
        
        p {
        text-align:center;
        border: 1px solid black;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
        margin-bottom: 10px;
        width:fit-content;
        display:flex;
        justify-content: center; 
        align-items: center; 
        margin: 5px auto;
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
 
   <h2><span class="unique-heading">HIV</span></h2>

    <div id="patientReports">
        <?php
        // Database connection
        require_once 'db.php';

        // Check if the patient ID is provided
        if(isset($_GET['id'])) {
            // Fetch report data from the database based on the provided patient ID
            $id = $_GET['id'];
            $sql = "SELECT * FROM hiv WHERE id = $id";
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
                        <th width="35%"><?php echo $row['ref_by'] ?></th>
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
        </thead> 
        </table>
        <br>
        <p> REPORT </p>

        <table border="1" id="table2" >
                <tbody>
                    <!--<tr>-->
                    <!--    <td>-->
                            <div id="textarea-container">
                                <textarea cols="100" rows="20">
                                    <?php echo $row['details'] ?>
                                </textarea>
                            </div>
                    <!--    </td>-->
                    <!--</tr>-->
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
