<?php
include 'conn.php';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($search !== '') {
    // Check if search matches a category name
    $catStmt = $conn->prepare("SELECT categoryid FROM categories WHERE name = ?");
    $catStmt->bind_param("s", $search);
    $catStmt->execute();
    $catResult = $catStmt->get_result();

    if ($catResult->num_rows > 0) {
        $category = $catResult->fetch_assoc();
        header("Location: categories1.php?id=" . $category['categoryid']);
        exit();
    }

    // Else search for tools by tool name
    $toolStmt = $conn->prepare("SELECT tools.*, categories.categoryname 
                                FROM tools 
                                JOIN categories ON tools.categoryid = categories.categoryid 
                                WHERE tools.name LIKE ?");
    $param = "%$search%";
    $toolStmt->bind_param("s", $param);
    $toolStmt->execute();
    $results = $toolStmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search AI Tools</title>
  <link rel="stylesheet" href="styles/styl17.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
  <h1 class="logo">AIFindr</h1>
  <nav>
    <a href="home.php">Home</a>
    <a href="search.php">Search</a>
    <a href="submit.php">Submit Tool</a>
    <a href="user.php"><i class="fa fa-user-circle"></i></a>
  </nav>
</header>

<div class="search-container">
  <form class="search-bar" action="search.php" method="get">
    <input type="text" name="q" placeholder="Search for AI tools..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit"><i class="fa fa-search"></i></button>
  </form>
</div>

<div class="results">
  <?php if ($search !== '' && $results && $results->num_rows > 0): ?>
    <?php while ($row = $results->fetch_assoc()): ?>
      <div class="tool-card">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><strong>Category: </strong><?php echo htmlspecialchars($row['categoryname']); ?></p>
        <p><strong>Description: </strong><?php echo htmlspecialchars($row['description']); ?></p>
        <p><strong>Pricing: </strong><?php echo htmlspecialchars($row['pricing']); ?></p>
        <p><a href="<?php echo htmlspecialchars($row['websitelink']); ?>" target="_blank">Visit Website</a></p>
      </div>
    <?php endwhile; ?>
  <?php elseif ($search !== ''): ?>
    <div class="no-results">No tools found for "<strong><?php echo htmlspecialchars($search); ?></strong>"</div>
  <?php endif; ?>
</div>

</body>
</html>
