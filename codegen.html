<?php
include 'conn.php';

$categoryId = isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;

if ($categoryId <= 0) {
    echo "Invalid category!";
    exit;
}

// Fetch category name
$categoryName = "Tools";
$catResult = mysqli_query($conn, "SELECT categoryname FROM categories WHERE categoryid = $categoryId");
if ($catRow = mysqli_fetch_assoc($catResult)) {
    $categoryName = $catRow['categoryname'];
}

// Handle feedback
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback'], $_POST['toolid'])) {
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $toolid = intval($_POST['toolid']);
    $updateQuery = "UPDATE tools SET feedback = '$feedback' WHERE toolid = $toolid";
    mysqli_query($conn, $updateQuery);
    header("Location: category.php?categoryid=$categoryId");
    exit;
}

// Fetch tools
$query = "SELECT * FROM tools WHERE categoryid = $categoryId";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $categoryName; ?> - Tools</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
        }
        .tool-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .tool-card h3 { margin-top: 0; }
        .feedback-box {
            margin-top: 10px;
        }
        textarea {
            width: 100%;
            resize: vertical;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;"><?php echo $categoryName; ?> Tools</h2>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="tool-card">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
        <p><strong>Pricing:</strong> <?php echo htmlspecialchars($row['pricing']); ?></p>
        <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($row['websitelink']); ?>" target="_blank">Visit</a></p>
        
        <div class="feedback-box">
            <form method="post" action="">
                <input type="hidden" name="toolid" value="<?php echo $row['toolid']; ?>">
                <label>Leave Feedback:</label><br>
                <textarea name="feedback" rows="3"><?php echo htmlspecialchars($row['feedback']); ?></textarea><br><br>
                <button type="submit">Submit Feedback</button>
            </form>
        </div>
    </div>
<?php } ?>

<div class="back-link">
    <a href="categories.php">← Back to Categories</a>
</div>

</body>
</html>
