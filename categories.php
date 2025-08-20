<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Tool Categories - AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .category-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        .category-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: #4285f4;
        }
        .category-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .nav-link {
             position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: linear-gradient(135deg, #4285f4, #34a853);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .page-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
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
                        <a class="nav-link" href="search.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
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
        <h1 class="display-4 text-center mb-5 fw-bold page-title">Browse AI Tools by Category</h1>
        
        <div class="row g-4">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=1" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-comments category-icon"></i>
                    <h5 class="text-center mb-0">Chatbots</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=2" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-images category-icon"></i>
                    <h5 class="text-center mb-0">Image Tools</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=3" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-code category-icon"></i>
                    <h5 class="text-center mb-0">Code Generators</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=4" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-pen-fancy category-icon"></i>
                    <h5 class="text-center mb-0">Writing Assistants</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=5" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-headphones category-icon"></i>
                    <h5 class="text-center mb-0">Audio Tools</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=6" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-video category-icon"></i>
                    <h5 class="text-center mb-0">Video Tools</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=7" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-tasks category-icon"></i>
                    <h5 class="text-center mb-0">Productivity</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=8" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-graduation-cap category-icon"></i>
                    <h5 class="text-center mb-0">Education</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=9" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-search category-icon"></i>
                    <h5 class="text-center mb-0">Search & Research</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=10" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-paint-brush category-icon"></i>
                    <h5 class="text-center mb-0">Design Tools</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=11" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-chart-line category-icon"></i>
                    <h5 class="text-center mb-0">Marketing & SEO</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=12" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-database category-icon"></i>
                    <h5 class="text-center mb-0">Data Analytics</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=13" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-microphone-alt category-icon"></i>
                    <h5 class="text-center mb-0">Voice Cloning</h5>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="categories1.php?id=14" class="category-card h-100 rounded-4 p-4 d-flex flex-column align-items-center">
                    <i class="fas fa-user-astronaut category-icon"></i>
                    <h5 class="text-center mb-0">AI Avatars & Animation</h5>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
