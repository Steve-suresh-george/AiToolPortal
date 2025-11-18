<?php
session_start();

// Include your database connection file
require_once 'conn.php';

// Get the logged-in user's username from the session
$current_username = '';
if (isset($_SESSION['username'])) {
    $current_username = $_SESSION['username'];
}

// Track whether user is logged in
$not_logged_in = empty($current_username);

// SQL query to get the top 5 most recently used tools
$sql = "SELECT t.toolid, t.name, urt.lastusedat
    FROM userrecenttools AS urt
    JOIN tools AS t ON urt.toolid = t.toolid
    WHERE urt.username = ?
    ORDER BY urt.lastusedat DESC
    LIMIT 5";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $current_username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $recent_tools = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die("Could not fetch recent tools: " . $e->getMessage());
}

// If user is not logged in, ensure recent_tools is empty to avoid accidental data exposure
if ($not_logged_in) {
    $recent_tools = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Used</title>
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
        .tool-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
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

            <h1 class="mb-4">Recently Used</h1>

        </div>

         <p class="text-muted"><a href="user.php" class="text-white text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a></p>
        <hr class="text-white-50 mb-5">
        <div class="row">
            <?php if (!empty($recent_tools) && !$not_logged_in): ?>
                <div class="col-lg-6">
                    <div class="tool-card shadow">
                        <ul>
                            <?php foreach ($recent_tools as $tool): ?>
                                <li class="d-flex justify-content-between align-items-center py-2">
                                    <span><?php echo htmlspecialchars($tool['name']); ?></span>
                                    <small><?php echo htmlspecialchars($tool['lastusedat']); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            <?php elseif ($not_logged_in): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        Please <a href="login.php" class="alert-link">log in</a> to view your recently used tools.
                    </div>
                </div>

            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        You haven't visited any tools yet! Visit the <a href="categories.php" class="alert-link">Categories page</a> to find some.
                    </div>
                </div>
            <?php endif; ?>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Close the statement and connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>