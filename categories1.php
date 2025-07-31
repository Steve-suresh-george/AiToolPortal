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
<html>
<head>
    <title><?php echo $categoryName; ?> Tools</title>
    <link rel="stylesheet" href="styles/styl17.css">
</head>
<body>
     <header>
         <div class="navbar">
            <h1 class="heading">AIFindr</h1>
                <div class="nav">
                    <a class="a2 nave" href="categories.php">Categories</a>
                    <a  class="a3 nave" href="suggest.php" target="_blank">Submit Tool</a>
                    <a class="a4 nave" href="signup.html">Sign up</a>
                    <a href="user.php"><i class="fa-solid fa-circle-user"></i></a>
                </div>
        </div>
    </header>
    <h2><?php echo $categoryName; ?> Tools</h2>
    <?php if ($feedbackSuccess): ?>
        <p style="color: green; font-weight: bold;">Feedback submitted successfully!</p>
    <?php endif; ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="tool-card">
            <h3><?php echo $row['name']; ?></h3>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Pricing:</strong> <?php echo $row['pricing']; ?></p>
            <p><a href="<?php echo $row['websitelink']; ?>" target="_blank">Visit Tool</a></p>

            <form method="POST" class="feedback-form">
                <input type="hidden" name="toolid" value="<?php echo $row['toolid']; ?>">
                <textarea name="feedback" rows="2" cols="40" placeholder="Your feedback..."><?php echo $row['feedback']; ?></textarea><br>
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>
</html>
