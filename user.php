<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - AIFindr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            color: white;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: white;
        }
        .nav-links {
            list-style: none;
            padding: 0;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .nav-links i {
            margin-right: 10px;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .welcome-text {
            color: white;
            margin-bottom: 30px;
        }
        .search-container {
            max-width: 500px;
            margin-bottom: 30px;
        }
        .search-container .input-group {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            overflow: hidden;
        }
        .search-container input {
            background: transparent;
            border: none;
            color: white;
            padding: 15px;
        }
        .search-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .search-container .btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            color: white;
            height: 100%;
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">AIFindr</div>
            <ul class="nav-links">
                <li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
                <li><a href="categories.php"><i class="fa-solid fa-list"></i>Categories</a></li>
                <li><a href="suggest.php"><i class="fa-solid fa-wrench"></i>Submit tool</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="welcome-text">
                <h2>Welcome, <?php echo $_SESSION['username']; ?> 👋</h2>
            </div>

            <div class="search-container">
                <form action="search.php" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search tools..." name="query">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="feedback.php" style="text-decoration: none;">
                        <div class="dashboard-card">
                            <h3>Feedbacks</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="recently1.php" style="text-decoration: none;">
                        <div class="dashboard-card">
                            <h3>Recently Used</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                  <a href="suggestion.php" style="text-decoration: none;">
                    <div class="dashboard-card">
                        <h3>Suggestions</h3>
                    </div>
                  </a>
                </div>
                 <div class="col-md-4">
                  <a href="usersavedtools.php" style="text-decoration: none;">
                    <div class="dashboard-card">
                        <h3>Saved Tools</h3>
                    </div>
                  </a>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
