<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total records for a table
function getTotalRecords($table)
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        // Log database error
        error_log("Error in getTotalRecords function: " . $conn->error);
        return 0;
    }
}

function getReportHistoryData() {
    global $conn;

    $data = [];
    $tables = ['biochemical', 'hemotology', 'stool', 'urine', 'widalTest', 'asoRa', 'crp', 'serum', 'hiv', 'semen1', 'semen2'];

    // Initialize data array for each month
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    foreach ($months as $month) {
        $data[$month] = 0;
    }

    foreach ($tables as $table) {
        $sql = "SELECT DATE_FORMAT(date, '%b') AS month, COUNT(*) AS reports FROM $table GROUP BY DATE_FORMAT(date, '%b')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[$row['month']] += $row['reports'];
            }
        }
    }

    return array_values($data); // Return values as an array

}

// Function to get total patients for a table
function getTotalPatients($table)
{
    global $conn;

    $sql = "SELECT COUNT(DISTINCT uhid) AS total FROM $table";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        // Log database error
        error_log("Error in getTotalPatients function: " . $conn->error);
        return 0;
    }
}

// Get total patients for each table
$totalPatients = [
    'biochemical' => getTotalPatients('biochemical'),
    'hemotology' => getTotalPatients('hemotology'),
    'stool' => getTotalPatients('stool'),
    'urine' => getTotalPatients('urine'),
    'widalTest' => getTotalPatients('widalTest'),
    'asoRa' => getTotalPatients('asoRa'),
    'crp' => getTotalPatients('crp'),
    'serum' => getTotalPatients('serum'),
    'hiv' => getTotalPatients('hiv'),
    'semen1' => getTotalPatients('semen1'),
    'semen2' => getTotalPatients('semen2'),
];

// Get total records for each table
$totalRecords = [
    'biochemical' => getTotalRecords('biochemical'),
    'hemotology' => getTotalRecords('hemotology'),
    'stool' => getTotalRecords('stool'),
    'urine' => getTotalRecords('urine'),
    'widalTest' => getTotalRecords('widalTest'),
    'asoRa' => getTotalRecords('asoRa'),
    'crp' => getTotalRecords('crp'),
    'serum' => getTotalRecords('serum'),
    'hiv' => getTotalRecords('hiv'),
    'semen1' => getTotalRecords('semen1'),
    'semen2' => getTotalRecords('semen2'),
];

$grandTotal = array_sum($totalRecords);

// Calculate percentages
$percentages = [];
foreach ($totalRecords as $tableName => $recordCount) {
    $percentage = ($recordCount / $grandTotal) * 100;
    $percentages[$tableName] = round($percentage, 2);
}

// Get report history data
$reportHistoryData = getReportHistoryData();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

/* General Styles */
body {
    margin: 5px;
    font-family: 'Poppins', sans-serif;
    background-color: #1a1a1a;
    color: #e0e0e0;
}

/* Header */
.header {
    background: linear-gradient(to right, #dc143c, #800000);
    color: #fff;
    padding: 1.2rem 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
}

.header h1 {
    font-size: 1.7rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.header .user-info {
    display: flex;
    align-items: center;
}

.header .user-info img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin-right: 0.8rem;
    box-shadow: 0 0 10px rgba(220, 20, 60, 0.5);
    border: 2px solid rgba(220, 20, 60, 0.6);
}

/* Charts */
.chart-container {
    display: grid;
    grid: auto auto / auto auto auto auto;
    grid-gap: 15px;
    padding: 15px;
    text-align: center;
    gap: 2.2rem;
    margin-bottom: 2.5rem;
}

.chart-container canvas {
    max-width: 600px;
    max-height: 500px;
    box-shadow: 0 4px 15px rgba(220, 20, 60, 0.2);
    border-radius: 1rem;
    padding: 15px;
    background-color: #252525;
    border: 1px solid rgba(220, 20, 60, 0.2);
}

/* Report Types */
.report-types {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.2rem;
}

.report-type {
    background-color: #252525;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(220, 20, 60, 0.2);
    cursor: pointer;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    border: 1px solid rgba(220, 20, 60, 0.1);
}

.report-type:hover {
    transform: translateY(-7px);
    box-shadow: 0 6px 20px rgba(220, 20, 60, 0.3);
    border-color: rgba(220, 20, 60, 0.4);
}

.report-type i {
    font-size: 2.2rem;
    margin-right: 1.2rem;
    color: #dc143c;
    transition: all 0.4s ease;
}

.report-type:hover i {
    transform: scale(1.3);
    color: #ff1744;
}

.report-type h5 {
    margin: 0;
    font-weight: 600;
    color: #e0e0e0;
    transition: all 0.3s ease;
}

.report-type:hover h5 {
    color: #ff1744;
}

.report-type p {
    margin: 0;
    color: #9e9e9e;
    font-size: 0.95rem;
}

.report-type::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(220, 20, 60, 0.1);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
    z-index: -100;
}

.report-type:hover::before {
    transform: translateX(100%);
}

/* Stats */
.stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2.5rem;
}

.stat {
    background-color: #252525;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(220, 20, 60, 0.2);
    text-align: center;
    flex: 1;
    margin: 0 0.8rem;
    transition: all 0.4s ease;
    border: 1px solid rgba(220, 20, 60, 0.1);
}

.stat:hover {
    transform: translateY(-7px);
    box-shadow: 0 6px 20px rgba(220, 20, 60, 0.3);
    border-color: rgba(220, 20, 60, 0.4);
}

.stat i {
    font-size: 2.2rem;
    color: #dc143c;
    margin-bottom: 0.8rem;
    transition: all 0.3s ease;
}

.stat:hover i {
    color: #ff1744;
    transform: scale(1.2);
}

.stat h4 {
    margin: 0;
    font-weight: 600;
    color: #e0e0e0;
}

.stat p {
    margin: 0.7rem 0 0;
    color: #9e9e9e;
    font-size: 0.95rem;
}

/* Animations */
@keyframes slide-up {
    0% {
        transform: translateY(25px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.chart-container,
.stats,
.report-types {
    animation: slide-up 0.6s ease-out;
}

</style>
    
    </head>
<body>
    <div class="dashboard-container">
         <div class="content">
            <div class="stats">
                <div class="stat">
                    <i class="fas fa-users"></i>
                    <h4><?php echo array_sum($totalPatients); ?></h4>
                    <p>Total Patients</p>
                </div>
                <div class="stat">
                    <i class="fas fa-file-alt"></i>
                    <h4><?php echo $grandTotal; ?></h4>
                    <p>Total Reports</p>
                </div>
            </div>

            <div class="chart-container">
                <div>
                    <h2 class="mb-4">Record Distribution</h2>
                    <canvas id="recordDistributionChart"></canvas>
                </div>
                <div>
                    <h2 class="mb-4">Report Generation History</h2>
                    <canvas id="reportHistoryChart"></canvas>
                </div>
            </div>

             <div class="report-types">
                 <?php foreach ($totalRecords as $tableName => $recordCount): ?>
                <div class="col-md-4 mb-4">
                    <div class="report-type">
                        <i class="fas fa-file-medical me-4"></i>
                        <h5><?php echo ucwords(str_replace('_', ' ', $tableName)); ?></h5> &nbsp;</p>
                        <p>Total Records: <?php echo $recordCount; ?></p> &nbsp;
                        <p>Total Patients: <?php echo $totalPatients[$tableName]; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx1 = document.getElementById('recordDistributionChart').getContext('2d');
            const recordDistributionChart = new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_keys($totalRecords)); ?>,
                    datasets: [{
                        label: 'Number of Records',
                        data: <?php echo json_encode(array_values($totalRecords)); ?>,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#C94C4C', '#00FF00', '#FF00FF', '#FFA500', '#800080'
                        ],
                        borderColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#C94C4C', '#00FF00', '#FF00FF', '#FFA500', '#800080'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed) {
                                        let percentage = <?php echo json_encode($percentages); ?>[context.dataIndex];
                                        label += context.parsed.toLocaleString() + ' (' + (percentage !== undefined ? percentage : 0) + '%)';
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'right',
                            align: 'center',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                padding: 10,
                                usePointStyle: true
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const ctx2 = document.getElementById('reportHistoryChart').getContext('2d');
            const reportHistoryChart = new Chart(ctx2, {
                type:'line',
               data: {
                   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                   datasets: [{
                       label: 'Reports Generated',
                       data: <?php echo json_encode($reportHistoryData); ?>,
                       fill: false,
                       borderColor: '#007bff',
                       tension: 0.1
                   }]
               },
               options: {
                   scales: {
                       y: {
                           beginAtZero: true
                       }
                   }
               }
           });
       });
       
       
   </script>
</body>
</html>