<?php
require_once 'db.php'; // Include the database connection file

// Initialize response array
$response = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data (you can add more validation here)
    if (empty($username) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Username and Password are required';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement to insert the new user
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        // Prepare and bind the statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $username, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'User registered successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error preparing statement: ' . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | PathLab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --error-color: #ef4444;
            --background: #f1f5f9;
            --white: #ffffff;
            --input-border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            line-height: 1.6;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            max-width: 1000px;
            width: 100%;
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .auth-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--white);
        }

        .banner-logo {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .banner-content {
            margin: 2rem 0;
        }

        .banner-content h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .banner-content p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .auth-form {
            padding: 3rem 2rem;
            background: var(--white);
        }

        .form-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-header h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .submit-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
        }

        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
        }

        .auth-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .success-message {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
            }

            .auth-banner {
                display: none;
            }

            .auth-form {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-banner">
            <div class="banner-logo">
                <i class="fas fa-flask"></i> PathLab
            </div>
            <div class="banner-content">
                <h1>Welcome to PathLab Management System</h1>
                <p>Join our platform to streamline your laboratory operations and enhance patient care with our comprehensive management solution.</p>
            </div>
            <div class="banner-footer">
                <p>© 2024 PathLab. All rights reserved.</p>
            </div>
        </div>

        <div class="auth-form">
            <div class="form-header">
                <h2>Create your account</h2>
                <p>Enter your details to get started</p>
            </div>

            <?php
            if (isset($response['message'])) {
                $messageClass = $response['status'] === 'success' ? 'success-message' : 'error-message';
                echo "<div class='message $messageClass'>{$response['message']}</div>";
            }
            ?>

            <form method="post" action="/pathologylab-main/registerUser.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Enter your username"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Enter your email"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            placeholder="Confirm your password"
                            required
                        >
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Create Account
                </button>

                <div class="auth-links">
                    Already have an account? <a href="login.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>