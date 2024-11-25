<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f6fa;
            --border-color: #e1e4e8;
            --text-color: #2c3e50;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f8f9fa;
        }

        .content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .tab {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1px;
        }

        .tablinks {
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            font-size: 1rem;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tablinks i {
            font-size: 1.1rem;
        }

        .tablinks:hover {
            color: var(--primary-color);
            background-color: var(--secondary-color);
        }

        .tablinks.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            font-weight: 500;
        }

        .tabcontent {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--secondary-color);
            font-weight: 500;
        }

        input[type="number"] {
            width: 120px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #357abd;
        }

        /* Hide inactive tabs */
        .tabcontent {
            display: none;
        }

        .tabcontent:first-of-type {
            display: block;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .tab {
                flex-direction: column;
                gap: 0;
            }

            .tablinks {
                width: 100%;
                justify-content: center;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .content {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php 
    session_start();

    // Check if the user is logged in and has the required permissions
    if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || $_SESSION['username'] !== 'admin') {
        // Redirect to the login page or display an error message
        header("Location: login.php");
        exit();
    }

    // Database connection
    require_once 'db.php';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle the form submission and update the prices
        require_once 'update_prices.php';
    }

    // Fetch the current test prices from the database
    $sql = "SELECT test_type, price FROM test_prices";
    $result = $conn->query($sql);
    ?>

    <div class="content">
        <h2>Settings</h2>
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'TestPrices')">
                <i class="fas fa-vial"></i> Test Prices
            </button>
            <button class="tablinks" onclick="openTab(event, 'GeneralSettings')">
                <i class="fas fa-sliders-h"></i> General Settings
            </button>
            <button class="tablinks" onclick="openTab(event, 'UserManagement')">
                <i class="fas fa-users"></i> User Management
            </button>
        </div>

        <div id="TestPrices" class="tabcontent">
            <h3>Test Prices</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <table>
                    <tr>
                        <th>Test Type</th>
                        <th>Current Price</th>
                        <th>New Price</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['test_type']) . "</td>";
                            echo "<td>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td><input type='number' step='0.01' name='price[" . htmlspecialchars($row['test_type']) . "]' value='" . htmlspecialchars($row['price']) . "'></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
                <button type="submit">Update Prices</button>
            </form>
        </div>

        <div id="GeneralSettings" class="tabcontent">
            <h3>General Settings</h3>
            <!-- Add your general settings form or content here -->
        </div>

        <div id="UserManagement" class="tabcontent">
            <h3>User Management</h3>
            <!-- Add your user management content here -->
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html>