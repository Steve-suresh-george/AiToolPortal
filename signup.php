<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['fname'];
    $lastname  = $_POST['lname'];
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: user.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .signup-form {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .form-control, .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #4285f4;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .page-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .register-btn {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: transform 0.3s ease;
        }
        .register-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
        }
        .form-label {
            color: #4285f4;
            font-weight: 500;
        }
        .login-link {
            color: #fff;
            text-decoration: none;
        }
        .login-link a {
            color: #4285f4;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .login-link a:hover {
            color: #34a853;
        }
    </style>
</head>
<body class="bg-dark">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.html">
                <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h1 class="display-5 text-center mb-4 fw-bold page-title">
                    Create an Account
                </h1>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="signup-form rounded-4 p-4">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fname" class="form-label">
                                    <i class="fas fa-user me-2"></i>First Name
                                </label>
                                <input type="text" class="form-control" id="fname" name="fname" 
                                       placeholder="Enter your first name" required>
                                <div class="invalid-feedback text-light">
                                    Please enter your first name
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="lname" class="form-label">
                                    <i class="fas fa-user me-2"></i>Last Name
                                </label>
                                <input type="text" class="form-control" id="lname" name="lname" 
                                       placeholder="Enter your last name" required>
                                <div class="invalid-feedback text-light">
                                    Please enter your last name
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-at me-2"></i>Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Choose a username" required>
                            <div class="invalid-feedback text-light">
                                Please choose a username
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Create a password" required>
                            <div class="invalid-feedback text-light">
                                Please create a password
                            </div>
                        </div>

                        <button type="submit" class="btn register-btn text-white w-100 py-2 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>

                        <p class="text-center mb-0 login-link">
                            Already have an account? <a href="login.php">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Form Validation Script -->
    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>