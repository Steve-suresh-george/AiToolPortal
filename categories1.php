<?php
include 'conn.php';

// Get category ID from URL
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get category name
$categoryQuery = "SELECT name FROM categories WHERE categoryid = $categoryId";
$categoryResult = $conn->query($categoryQuery);
$categoryName = $categoryResult->num_rows > 0 ? $categoryResult->fetch_assoc()['name'] : 'Unknown Category';

// Handle feedback submission
// Feedback success message
$feedbackSuccess = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'], $_POST['toolid'])) {
    $feedback = $_POST['feedback'];
    $toolid = intval($_POST['toolid']);
    $stmt = $conn->prepare("UPDATE tools SET feedback = ? WHERE toolid = ?");
    if ($stmt) {
        $stmt->bind_param("si", $feedback, $toolid);
        if ($stmt->execute()) {
            $feedbackSuccess = true;
        }
        $stmt->close();
    }
}

// Get all tools in this category
$sql = "SELECT * FROM tools WHERE categoryid = $categoryId";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $categoryName; ?> Tools - AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .tool-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .category-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .feedback-form textarea {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .feedback-form textarea:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #4285f4;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
            color: white;
        }
        .visit-site-btn {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: transform 0.3s ease;
        }
        .visit-site-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
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
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.html">Sign up</a>
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
        <h1 class="display-4 text-center mb-5 fw-bold category-title">
            <?php echo $categoryName; ?> Tools
        </h1>

        <?php if ($feedbackSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>Feedback submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="tool-card rounded-4 p-4 text-white h-100">
                        <h3 class="h4 mb-3"><?php echo $row['name']; ?></h3>
                        
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">Description</h6>
                            <p class="text-light mb-3"><?php echo $row['description']; ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">Pricing</h6>
                            <p class="text-light mb-3"><?php echo $row['pricing']; ?></p>
                        </div>

                        <a href="<?php echo $row['websitelink']; ?>" target="_blank" 
                           class="btn visit-site-btn text-white mb-4">
                            <i class="fas fa-external-link-alt me-2"></i>Visit Site
                        </a>

                        <form method="POST" class="feedback-form">
                            <input type="hidden" name="toolid" value="<?php echo $row['toolid']; ?>">
                            <div class="form-group">
                                <label class="text-primary mb-2">Your Feedback</label>
                                <textarea name="feedback" class="form-control mb-3" rows="4" 
                                    placeholder="Share your experience with this tool..."><?php echo $row['feedback']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
