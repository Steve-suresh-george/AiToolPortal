<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'conn.php';

// Get the logged-in user's username from the session
$current_username = $_SESSION['username'];

// Fetch all feedback entries for the logged-in user
// This assumes you have a `feedback` table with a `username` column
$query = "SELECT t.name, f.comment, f.createdat
          FROM feedback AS f
          JOIN tools AS t ON f.toolid = t.toolid
          WHERE f.username = ?
          ORDER BY f.createdat DESC";

// Using prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $current_username);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .feedback-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .tool-name {
            color: #4285f4;
            font-weight: 600;
        }
        .rating {
            color: #ffd700;
        }
        .date {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        .page-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .back-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
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
            <h1 class="page-title display-5 mb-0">My Feedback</h1>
        </div>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-12">
                        <div class="feedback-card p-4 rounded-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h3 class="tool-name h5 mb-1">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </h3>
                                </div>
                                <?php if (isset($row['createdat'])): ?>
                                    <span class="date"><?php echo date("F j, Y", strtotime($row['createdat'])); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-2"><?php echo htmlspecialchars($row['comment']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x mb-3 text-secondary"></i>
                <h3>No Feedback Found</h3>
                <p class="text-muted">You have not submitted any feedback yet.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>