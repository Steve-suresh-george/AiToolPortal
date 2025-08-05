<?php
// DB Connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'miniproject'; // Change this

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $link = trim($_POST['link']);

  if ($name && $description && $link) {
    $stmt = $conn->prepare("INSERT INTO suggestions (name, description, websitelink) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $link);

    if ($stmt->execute()) {
      $success = "Tool suggested successfully!";
    } else {
      $error = "Error: " . $conn->error;
    }

    $stmt->close();
  } else {
    $error = "All fields are required.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest a Tool - AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .suggestion-form {
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
        .submit-btn {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: transform 0.3s ease;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
        }
        .form-label {
            color: #4285f4;
            font-weight: 500;
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
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user.php">
                            <i class="fa-solid fa-circle-user fa-lg"></i>
                        </a>
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
                    Suggest a New AI Tool
                </h1>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="suggestion-form rounded-4 p-4">
                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-robot me-2"></i>Tool Name
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="Enter the AI tool's name" required>
                            <div class="invalid-feedback text-light">
                                Please provide the tool name
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                    placeholder="Describe what this AI tool does..." required></textarea>
                            <div class="invalid-feedback text-light">
                                Please provide a description
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="link" class="form-label">
                                <i class="fas fa-link me-2"></i>Tool Link
                            </label>
                            <input type="url" class="form-control" id="link" name="link" 
                                   placeholder="https://..." required>
                            <div class="invalid-feedback text-light">
                                Please provide a valid URL
                            </div>
                        </div>

                        <button type="submit" class="btn submit-btn text-white w-100 py-2">
                            <i class="fas fa-paper-plane me-2"></i>Submit Suggestion
                        </button>
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
