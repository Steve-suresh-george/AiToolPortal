<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - AIFindr</title>
  <link rel="stylesheet" href="styles/styl5.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="logo">AIFindr</div>
      <ul class="nav-links">
        <a href="home.html"><li><i class="fas fa-home"></i>Home</li></a>
        <a href="categories.html"><li><i class="fa-solid fa-list"></i> Categories</li></a>
        <a href="suggest.php"><li><i class="fa-solid fa-wrench"></i> Submit tool</li></a>
      </ul>
    </aside>

    <main class="main-content">
      <header class="top-bar">
        <h2>Welcome back, <?php echo $_SESSION['username']; ?> 👋</h2>
        <div class="search-bar">
          <input type="text" placeholder="Search tools...">
          <button><i class="fas fa-search"></i></button>
        </div>
      </header>

      <section class="cards">
        <div class="card">
          <h3>Saved Tools</h3>
          <p>5 tools saved</p>
        </div>
        <div class="card">
          <h3>Recently Used</h3>
          <p>ChatGPT, Claude</p>
        </div>
        <div class="card">
          <h3>Suggestions</h3>
          <p>Try Gemini or Perplexity AI</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
