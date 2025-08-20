<?php
session_start();

// Include your database connection file
require_once 'conn.php';

// Get the logged-in user's username from the session
$current_username = '';
if (isset($_SESSION['username'])) {
    $current_username = $_SESSION['username'];
}

// SQL query to get the top 5 most recently used tools
// Note: Changed "userrecenttools" to "user_recent_tools" for consistency.
$sql = "SELECT t.toolid,t.name
        FROM userrecenttools AS urt
        JOIN tools AS t ON urt.toolid = t.toolid
        WHERE urt.username = ?
        ORDER BY urt.lastusedat DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $current_username);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $recent_tools = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    die("Could not fetch recent tools: " . $e->getMessage());
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
          <a class="nav-link" href="feedback.php">Feedback</a>
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

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recently Used</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($recent_tools)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_tools as $tool): ?>
                                <li class="list-group-item">
                                    <?php echo htmlspecialchars($tool['name']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="p-3 mb-0 text-muted">You haven't used any tools recently.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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