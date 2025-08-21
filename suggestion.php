<?php
session_start(); // Start the session to access logged-in user info
include 'conn.php'; // DB connection file

// 1. Check if the user is logged in.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 2. Get the logged-in user's username.
$loggedInUsername = $_SESSION['username'];

// 3. Prepare a SQL query to select suggestions submitted ONLY by this user.
$sql = "SELECT name, description, websitelink,status
        FROM suggestions 
        WHERE submitted_by = ?"; // The correct WHERE clause

$stmt = mysqli_prepare($conn, $sql);

// 4. Bind the username variable to the query. Use "s" for string.
mysqli_stmt_bind_param($stmt, "s", $loggedInUsername);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Suggestions</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
         body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
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
<body class="bg-dark text-white">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">
        <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav align-items-center ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">Search</a>
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
<div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title display-5 mb-0">Suggestions</h1>
    </div>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row g-4">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm text-dark">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($row['description']) . '</p>
                        <p class="card-text">
                            <a href="' . htmlspecialchars($row['websitelink']) . '">' . htmlspecialchars($row['websitelink']) . '</a>
                        </p>
                        <p class="card-text">' . htmlspecialchars($row['status']) . '</p>
                    </div>
                </div>
            </div>';
        }
        echo '</div>';
    } else {
        echo' <div class="text-center">
            <p class="lead">You haven\'t suggested any tools yet.</p>
            <a href="suggest.php" class="btn btn-success">Suggest a Tool Now!</a>
        </div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
