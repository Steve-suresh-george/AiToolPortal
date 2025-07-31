<?php
// db connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'miniproject'; // change this

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get search keyword
$search = isset($_GET['q']) ? $_GET['q'] : '';
$tools = [];

if ($search !== '') {
  $stmt = $conn->prepare("SELECT * FROM tools WHERE name LIKE ? OR description LIKE ?");
  $param = "%" . $search . "%";
  $stmt->bind_param("ss", $param, $param);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $tools[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results - AIFindr</title>
  <link rel="stylesheet" href="styles/styl6.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="header">
    <h1 class="logo">AIFindr</h1>
    <nav class="nav">
      <a href="categories.php">Categories</a>
      <a href="suggest.php">Submit Tool</a>
      <a href="signup.html">Sign Up</a>
      <a href="user.html"><i class="fas fa-user-circle"></i></a>
    </nav>
  </header>

  <div class="search-bar-container">
    <form action="search.php" method="get" style="display: flex; width: 100%;">
      <input type="text" name="q" placeholder="Search for AI tools..." class="search-input" value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <main class="results-container">
    <?php if ($search !== ''): ?>
      <h2 class="results-title">Search Results for "<?= htmlspecialchars($search) ?>"</h2>
    <?php else: ?>
      <h2 class="results-title">Search for an AI Tool</h2>
    <?php endif; ?>

    <div class="card-grid">
      <?php if (!empty($tools)): ?>
        <?php foreach ($tools as $tool): ?>
          <div class="tool-card">
            <h3><?= htmlspecialchars($tool['name']) ?></h3>
            <p><?= htmlspecialchars($tool['description']) ?></p>
            <a href="<?= htmlspecialchars($tool['link']) ?>" target="_blank">Visit Tool</a>
          </div>
        <?php endforeach; ?>
      <?php elseif ($search !== ''): ?>
        <p style="color: white; text-align: center;">No results found.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
