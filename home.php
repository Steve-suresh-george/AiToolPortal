<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIFindr - Discover AI Tools</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .search-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            position: relative;
        }
        .search-box {
            background: transparent;
            border: none;
            color: #fff;
            padding: 15px 25px;
            width: 100%;
        }
        .search-box:focus {
            outline: none;
        }
        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .search-button {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            color: white;
            transition: transform 0.2s;
        }
        .search-button:hover {
            transform: scale(1.05);
        }
        .tool-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .tool-link {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            padding: 8px 20px;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            transition: opacity 0.3s;
            display: inline-block;
        }
        .tool-link:hover {
            opacity: 0.9;
            color: white;
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
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign up</a>
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

    <!-- Search Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form action="search.php" method="GET" class="search-container d-flex align-items-center p-2">
                    <input type="text" name="q" class="search-box" placeholder="Search for AI tools.." />
                    <button type="submit" class="search-button d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tools Grid -->
    <div class="container py-5">
        <div class="row g-4">
            <!-- ChatGPT -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-robot fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">ChatGPT</h3>
                    </div>
                    <p class="small mb-4">ChatGPT is a powerful AI language model developed by OpenAI. It has been trained on a massive amount of text from the internet, allowing it to understand context, answer complex questions, summarize information, translate languages, and generate a wide variety of creative and technical text.</p>
                    <a href="https://chat.openai.com" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Character.ai -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-comments fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Character.ai</h3>
                    </div>
                    <p class="small mb-4">Character.AI is a conversational AI platform where the chatbots are built around specific personalities or "characters." Unlike a general-purpose AI like ChatGPT, each bot is designed to stay in character, allowing users to have immersive conversations.</p>
                    <a href="https://character.ai" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Claude -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-brain fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Claude</h3>
                    </div>
                    <p class="small mb-4">Claude is a family of large language models developed by Anthropic. They are designed to be helpful, harmless, and honest, with a particular focus on safety and constitutional AI principles.</p>
                    <a href="https://claude.ai" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- DALL-E -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-palette fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">DALL-E</h3>
                    </div>
                    <p class="small mb-4">DALL-E is a series of artificial intelligence models developed by OpenAI that can generate unique digital images from natural language descriptions, known as "prompts."</p>
                    <a href="https://openai.com/dall-e-2" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Gemini -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-star fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Gemini</h3>
                    </div>
                    <p class="small mb-4">Gemini is Google's family of multimodal AI models and the name of its generative AI chatbot. It's designed to be a versatile assistant, capable of understanding and generating various types of information.</p>
                    <a href="https://gemini.google.com" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Midjourney -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-image fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Midjourney</h3>
                    </div>
                    <p class="small mb-4">Midjourney is a popular generative artificial intelligence program and service that creates images from natural language descriptions, known as "prompts."</p>
                    <a href="https://www.midjourney.com" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Chatbots -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-message fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Chatbots</h3>
                    </div>
                    <p class="small mb-4">Chatbots are computer programs designed to simulate human conversation through text or voice. They can range from simple rule-based systems to advanced AI-powered models.</p>
                    <a href="#" class="tool-link mt-auto">Visit</a>
                </div>
            </div>

            <!-- Grammarly -->
            <div class="col-lg-3 col-md-6">
                <div class="tool-card rounded-4 p-4 text-white h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-spell-check fa-2x text-primary me-3"></i>
                        <h3 class="h5 mb-0">Grammarly</h3>
                    </div>
                    <p class="small mb-4">Grammarly is an AI-powered writing assistant that helps users improve their written English. It goes beyond basic spell-checking and grammar correction.</p>
                    <a href="https://www.grammarly.com" class="tool-link mt-auto">Visit</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
