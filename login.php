<?php
session_start();
require_once 'db.php'; // Include the database connection file

// Check if the user is already logged in
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // Redirect to the dashboard
    header("Location: pathology.php");
    exit();
}

// Handle Login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Input validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Compare the entered password with the hashed password in the database
        if (password_verify($password, $row['password'])) {
            // Authentication successful, set session variables
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            
            // Set remember me cookie if checked
            if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 30 days
                
                // Store token in database
                $update_token = "UPDATE users SET remember_token = ? WHERE id = ?";
                $token_stmt = $conn->prepare($update_token);
                $token_stmt->bind_param("si", $token, $row['id']);
                $token_stmt->execute();
            }
            
            header('Location: pathology.php');
            exit;
        }
    }

    // Authentication failed
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle Registration
if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Input validation
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['reg_error'] = 'Please fill in all fields';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['reg_error'] = 'Please enter a valid email address';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['reg_error'] = 'Passwords do not match';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Password strength validation
    if (strlen($password) < 8) {
        $_SESSION['reg_error'] = 'Password must be at least 8 characters long';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Check if username already exists
    $check_username = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['reg_error'] = 'Username already exists';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['reg_error'] = 'Email already registered';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user into database
    $insert_sql = "INSERT INTO users (fullname, email, username, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);
    
    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['success'] = 'Account created successfully. Please log in.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['reg_error'] = 'Registration failed. Please try again.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediLab - Pathology Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f6f7fe 0%, #e9eeff 100%);
            min-height: 100vh;
            display: grid;
            place-items: center;
            color: #1a1a1a;
        }

        .page-container {
            display: flex;
            gap: 2rem;
            max-width: 1000px;
            margin: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(10px);
        }

        .auth-section {
            padding: 3rem;
            width: 400px;
        }

        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 3rem;
            border-radius: 0 24px 24px 0;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 500px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .logo i {
            font-size: 1.5rem;
            color: #4f46e5;
        }

        .logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tab-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tab-button {
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: color 0.3s;
        }

        .tab-button.active {
            color: #4f46e5;
        }

        .tab-button::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #4f46e5;
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .tab-button.active::after {
            transform: scaleX(1);
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #1a1a1a;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: auto;
        }

        .forgot-password {
            color: #4f46e5;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        button[type="submit"]:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .hero-content h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .hero-content p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .features {
            list-style: none;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .features i {
            color: #22c55e;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .page-container {
                flex-direction: column;
            }

            .auth-section, .hero-section {
                width: 100%;
            }

            .hero-section {
                border-radius: 0 0 24px 24px;
            }
        }

        /* Add these new styles for messages */
        .message {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .error-message {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .success-message {
            background-color: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .message i {
            font-size: 1.25rem;
        }

        .error-message i {
            color: #dc2626;
        }

        .success-message i {
            color: #16a34a;
        }

        /* Style for form validation errors */
        .input-error {
            border-color: #dc2626 !important;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .error-text i {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <section class="auth-section">
            <div class="logo">
                <i class="fas fa-flask"></i>
                <h1>MediLab</h1>
            </div>
            
            <div class="tab-buttons">
                <button class="tab-button active" onclick="showForm('login')">Sign In</button>
                <button class="tab-button" onclick="showForm('register')">Create Account</button>
            </div>

            <!-- Login Form -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>" class="auth-form active" id="login-form">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="message error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>' . htmlspecialchars($_SESSION['error']) . '</span>
                            </div>';
                        unset($_SESSION['error']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo '<div class="message success-message">
                                <i class="fas fa-check-circle"></i>
                                <span>' . htmlspecialchars($_SESSION['success']) . '</span>
                            </div>';
                        unset($_SESSION['success']);
                    }
                    ?>    
            
            <div class="input-group">
                    <label for="login-username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               id="login-username" 
                               name="username" 
                               placeholder="Enter your username" 
                               required
                               class="<?php echo isset($_SESSION['username_error']) ? 'input-error' : ''; ?>">
                    </div>
                    <?php
                    if (isset($_SESSION['username_error'])) {
                        echo '<div class="error-text">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>' . htmlspecialchars($_SESSION['username_error']) . '</span>
                              </div>';
                        unset($_SESSION['username_error']);
                    }
                    ?>
                </div>

                <div class="input-group">
                    <label for="login-password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" name="login">Sign In</button>
            </form>

            <!-- Registration Form -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>" class="auth-form" id="register-form">
            <?php
                if (isset($_SESSION['reg_error'])) {
                    echo '<div class="message error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>' . htmlspecialchars($_SESSION['reg_error']) . '</span>
                          </div>';
                    unset($_SESSION['reg_error']);
                }
                ?>

                <div class="input-group">
                    <label for="reg-fullname">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               id="reg-fullname" 
                               name="fullname" 
                               placeholder="Enter your full name" 
                               required
                               class="<?php echo isset($_SESSION['fullname_error']) ? 'input-error' : ''; ?>"
                               value="<?php echo isset($_SESSION['old_fullname']) ? htmlspecialchars($_SESSION['old_fullname']) : ''; ?>">
                    </div>
                    <?php
                    if (isset($_SESSION['fullname_error'])) {
                        echo '<div class="error-text">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>' . htmlspecialchars($_SESSION['fullname_error']) . '</span>
                              </div>';
                        unset($_SESSION['fullname_error']);
                    }
                    ?>
                </div>

                <div class="input-group">
                    <label for="reg-email">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="reg-email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="reg-username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="reg-username" name="username" placeholder="Choose a username" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="reg-password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="reg-password" name="password" placeholder="Choose a password" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="reg-confirm-password">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <button type="submit" name="register">Create Account</button>
            </form>
        </section>

        <section class="hero-section">
            <div class="hero-content">
                <h2>Pathology Portal</h2>
                <p>Access your lab reports, manage patient data, and collaborate with your team in one secure platform.</p>
                
                <ul class="features">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time test results and analytics</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Secure patient data management</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Digital sample tracking system</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Integrated reporting tools</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <script>
        function showForm(formType) {
            // Update button states
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Show selected form
            document.querySelectorAll('.auth-form').forEach(form => {
                form.classList.remove('active');
            });
            if (formType === 'login') {
                document.getElementById('login-form').classList.add('active');
            } else {
                document.getElementById('register-form').classList.add('active');
            }
        }
    // Add client-side validation
    document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                let hasError = false;
                
                // Clear previous errors
                this.querySelectorAll('.error-text').forEach(error => error.remove());
                this.querySelectorAll('.input-error').forEach(input => input.classList.remove('input-error'));

                // Validate required fields
                this.querySelectorAll('input[required]').forEach(input => {
                    if (!input.value.trim()) {
                        hasError = true;
                        input.classList.add('input-error');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-text';
                        errorDiv.innerHTML = `
                            <i class="fas fa-exclamation-circle"></i>
                            <span>This field is required</span>
                        `;
                        input.parentElement.parentElement.appendChild(errorDiv);
                    }
                });

                // Validate email format
                const emailInput = this.querySelector('input[type="email"]');
                if (emailInput && emailInput.value.trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput.value.trim())) {
                        hasError = true;
                        emailInput.classList.add('input-error');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-text';
                        errorDiv.innerHTML = `
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please enter a valid email address</span>
                        `;
                        emailInput.parentElement.parentElement.appendChild(errorDiv);
                    }
                }

                // Validate password match in registration form
                if (this.id === 'register-form') {
                    const password = this.querySelector('#reg-password');
                    const confirmPassword = this.querySelector('#reg-confirm-password');
                    if (password.value !== confirmPassword.value) {
                        hasError = true;
                        confirmPassword.classList.add('input-error');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-text';
                        errorDiv.innerHTML = `
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Passwords do not match</span>
                        `;
                        confirmPassword.parentElement.parentElement.appendChild(errorDiv);
                    }
                }

                if (hasError) {
                    e.preventDefault();
                }
            });
        });

        // Auto-hide messages after 5 seconds
        document.querySelectorAll('.message').forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => message.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>