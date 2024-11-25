<?php
    // Include database connection
    require_once 'db.php';

    // Initialize variables
    $message = '';

    // Check if form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if table and ID parameters are provided in the URL
        if(isset($_GET['table']) && isset($_GET['id'])) {
            $table = $_GET['table'];
            $id = $_GET['id'];

            // Get column names from the table
            $sqlColumns = "SHOW COLUMNS FROM $table";
            $resultColumns = $conn->query($sqlColumns);
            $columns = array();
            while ($rowColumn = $resultColumns->fetch_assoc()) {
                $columns[] = $rowColumn['Field'];
            }

            // Initialize an empty array to store updated data
            $updatedData = array();

            // Iterate through the column names
            foreach ($columns as $column) {
                // Skip the 'id' column
                if ($column == 'id') continue;

                // Check if the column exists in the submitted form data
                if (isset($_POST[$column])) {
                    // Sanitize the input data
                    $updatedData[$column] = mysqli_real_escape_string($conn, $_POST[$column]);
                }
            }

            // Construct the SQL query to update the record
            $sqlUpdate = "UPDATE $table SET ";
            foreach ($updatedData as $key => $value) {
                $sqlUpdate .= "$key = '$value', ";
            }
            $sqlUpdate = rtrim($sqlUpdate, ', ');
            $sqlUpdate .= " WHERE id = $id";

            // Execute the update query
            if ($conn->query($sqlUpdate) === TRUE) {
                
            // Redirect to pathology.php with success message
            header("Location: pathology.php?table=" . urlencode($table) . "&success=Record updated successfully");
            exit();
                
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        } else {
            $message = "Table or ID not provided.";
        }
    } else {
        // If form is not submitted, display the edit form
        if(isset($_GET['table']) && isset($_GET['id'])) {
            $table = $_GET['table'];
            $id = $_GET['id'];

            // Fetch record from the database based on table and ID
            $sql = "SELECT * FROM $table WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                // Fetch record data
                $row = $result->fetch_assoc();

                // Get column names from the table
                $sqlColumns = "SHOW COLUMNS FROM $table";
                $resultColumns = $conn->query($sqlColumns);
                $columns = array();
                while ($rowColumn = $resultColumns->fetch_assoc()) {
                    $columns[] = $rowColumn['Field'];
                }
            } else {
                $message = "Record not found.";
            }
        } else {
            $message = "Table or ID not provided.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record - Laboratory Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --error-color: #ef4444;
            --background: #f3f4f6;
            --card-bg: #ffffff;
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            padding: 2rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background-color: #fef2f2;
            color: var(--error-color);
            border: 1px solid #fee2e2;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--background);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit <?php echo ucfirst($table); ?> Record</h1>
            <p>Update the information below</p>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?table=" . urlencode($table) . "&id=" . urlencode($id)); ?>">
            <div class="form-grid">
                <?php
                if (isset($columns)) {
                    foreach ($columns as $column) {
                        if ($column != 'id') {
                            $value = isset($row[$column]) ? htmlspecialchars($row[$column]) : '';
                            $isTextarea = strlen($value) > 100;
                            ?>
                            <div class="form-group">
                                <label for="<?php echo $column; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $column)); ?>
                                </label>
                                <?php if ($isTextarea): ?>
                                    <textarea 
                                        class="form-control" 
                                        name="<?php echo $column; ?>" 
                                        id="<?php echo $column; ?>"
                                    ><?php echo $value; ?></textarea>
                                <?php else: ?>
                                    <input 
                                        type="text" 
                                        class="form-control"
                                        name="<?php echo $column; ?>"
                                        id="<?php echo $column; ?>"
                                        value="<?php echo $value; ?>"
                                    >
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>

            <div class="actions">
                <a href="pathology.php?table=<?php echo urlencode($table); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        // Add animation to form inputs when focused
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.style.transition = 'all 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'none';
            });
        });
    </script>
</body>
</html>