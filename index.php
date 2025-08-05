<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 1500"><rect fill="%23415a77" width="2000" height="1500"/><defs><radialGradient id="a" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="%23778da9"/><stop offset="1" stop-color="%23415a77"/></radialGradient><linearGradient id="b" gradientUnits="userSpaceOnUse" x1="0" y1="750" x2="1550" y2="750"><stop offset="0" stop-color="%235c728f"/><stop offset="1" stop-color="%23415a77"/></linearGradient></defs><path fill="url(%23a)" d="M1999.5 1500h-2000v-1500h2000z"/></svg>');
            opacity: 0.1;
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        .brand-highlight {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
            letter-spacing: -1px;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
    </style>
</head>
<body class="bg-dark">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
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
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container content-wrapper">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-7" data-aos="fade-right">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Unlock Your Potential with the Right 
                        <span class="brand-highlight">AI Tools</span> 🚀
                    </h1>
                    <p class="lead text-light mb-5">
                        Discover, compare, and choose from hundreds of AI tools to enhance your workflow. 
                        Let AIFindr guide you to the perfect AI solution for your needs.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="categories.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Explore Tools
                        </a>
                        <a href="signup.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Now
                        </a>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-card rounded-4 p-4 text-white">
                                <i class="fas fa-robot fa-2x mb-3 text-primary"></i>
                                <h5>AI Chatbots</h5>
                                <p class="small mb-0">Engage with intelligent conversational AI assistants</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card rounded-4 p-4 text-white">
                                <i class="fas fa-image fa-2x mb-3 text-primary"></i>
                                <h5>Image Generation</h5>
                                <p class="small mb-0">Create stunning visuals with AI-powered tools</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card rounded-4 p-4 text-white">
                                <i class="fas fa-code fa-2x mb-3 text-primary"></i>
                                <h5>Code Assistance</h5>
                                <p class="small mb-0">Boost your coding productivity with AI</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-card rounded-4 p-4 text-white">
                                <i class="fas fa-pen-fancy fa-2x mb-3 text-primary"></i>
                                <h5>Content Creation</h5>
                                <p class="small mb-0">Generate engaging content effortlessly</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
