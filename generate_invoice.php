<?php     
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

require_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Invoice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --background: #f3f4f6;
            --card-background: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            padding: 2rem;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background-color: var(--card-background);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            padding: 2.5rem;
        }

        .invoice-header {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            max-width: 120px;
            height: auto;
            object-fit: contain;
        }

        .company-info {
            text-align: right;
        }

        .company-info h3 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .company-info p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin: 0.25rem 0;
        }

        .invoice-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .invoice-title h1 {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .invoice-number {
            background-color: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
            padding: 1.5rem;
            background-color: #f8fafc;
            border-radius: 8px;
        }

        .invoice-details p {
            margin: 0.75rem 0;
            display: flex;
            justify-content: space-between;
        }

        .invoice-details strong {
            color: var(--text-primary);
            min-width: 120px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 2rem;
        }

        .invoice-table th {
            background-color: #f1f5f9;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
        }

        .invoice-table th:first-child {
            border-top-left-radius: 8px;
        }

        .invoice-table th:last-child {
            border-top-right-radius: 8px;
        }

        .invoice-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .total {
            text-align: right;
            font-size: 1.25rem;
            color: var(--primary-color);
            margin: 2rem 0;
            padding: 1rem 0;
            border-top: 2px solid #e5e7eb;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .signature {
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            width: 100%;
            height: 1px;
            background-color: #000;
            margin-bottom: 0.5rem;
        }

        .footer {
            text-align: center;
            color: var(--text-secondary);
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .actions button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .actions button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
        }

        @media print {
            body {
                padding: 0;
                background-color: white;
            }

            .container {
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
            }

            .actions {
                display: none;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 1.5rem;
            }

            .invoice-header {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .company-info {
                text-align: center;
            }

            .invoice-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<?php

    $testType = $_GET['table'] ?? 'unknown';
    $id = $_GET['id'] ?? 0;

    $sql = "SELECT * FROM $testType WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $patientData = $result->fetch_assoc();

    $stmt->close();

    $sql = "SELECT laboratory_name, address, contact_number1, contact_number2, email FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $labInfo = $result->fetch_assoc();

    $stmt->close();

    $sql = "SELECT * FROM test_prices";
    $result = $conn->query($sql);

    $testPrices = [];
    while ($row = $result->fetch_assoc()) {
        $testPrices[strtoupper($row['test_type'])] = $row['price'];
    }

    $invoiceNumber = mt_rand(1000000, 9999999);

    $conn->close();
    ?>

    <div class="container">
        <div class="invoice-header">
            <div class="logo">
                <img src="logo.png" alt="Lab Logo">
            </div>
            <div class="company-info">
                <h3><?php echo $labInfo['laboratory_name']; ?></h3>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo $labInfo['address']; ?></p>
                <p><i class="fas fa-phone"></i> <?php echo $labInfo['contact_number1']; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo $labInfo['email']; ?></p>
            </div>
        </div>

        <div class="invoice-title">
            <h1>Invoice</h1>
            <span class="invoice-number">#<?php echo $invoiceNumber; ?></span>
        </div>

        <div class="invoice-details">
            <div class="patient-info">
                <h3>Patient Information</h3>
                <p><strong>Name:</strong> <?php echo $patientData['patient_name'] ?? 'N/A'; ?></p>
                <p><strong>UHID:</strong> <?php echo $patientData['uhid'] ?? 'N/A'; ?></p>
                <p><strong>Age:</strong> <?php echo $patientData['age'] ?? 'N/A'; ?></p>
                <p><strong>Gender:</strong> <?php echo $patientData['gender'] ?? 'N/A'; ?></p>
            </div>
            <div class="test-info">
                <h3>Test Information</h3>
                <p><strong>Test Type:</strong> <?php echo ucfirst($testType); ?></p>
                <p><strong>Date:</strong> <?php echo $patientData['date'] ?? 'N/A'; ?></p>
                <p><strong>Report Status:</strong> <span class="badge">Completed</span></p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo ucfirst($testType); ?> Test</td>
                    <td>
                        <?php
                        $testKey = strtoupper($testType);
                        $price = $testPrices[$testKey] ?? 'Price not available';
                        echo is_numeric($price) ? '₹ ' . number_format($price, 2) : "<span style='color: red;'>$price</span>";
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <p>Total Amount: ₹ <?php echo is_numeric($price) ? number_format($price, 2) : '0.00'; ?></p>
        </div>

        <div class="signature-section">
            <div class="signature">
                <div class="signature-line"></div>
                <p>Laboratory Director</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Authorized Signatory</p>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing our laboratory services</p>
            <p>&copy; <?php echo date('Y'); ?> <?php echo $labInfo['laboratory_name']; ?>. All rights reserved.</p>
        </div>

        <div class="actions">
            <button onclick="window.print()">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <button onclick="downloadPDF()">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>

    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const container = document.querySelector('.container');
            await pdf.html(container, {
                callback: (doc) => {
                    doc.save('lab_invoice.pdf');
                },
                x: 15,
                y: 15,
                width: 170,
                windowWidth: 650
            });
        }
    </script>
</body>
</html>