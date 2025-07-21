<?php
// DB Connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'miniproject'; // Change this

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $link = trim($_POST['link']);

  if ($name && $description && $link) {
    $stmt = $conn->prepare("INSERT INTO suggested_tools (name, description, link) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $link);

    if ($stmt->execute()) {
      $success = "Tool suggested successfully!";
    } else {
      $error = "Error: " . $conn->error;
    }

    $stmt->close();
  } else {
    $error = "All fields are required.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Suggest a Tool - AIFindr</title>
  <link rel="stylesheet" href="styl7.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <header>
  <div class="navbar">
     <h1 class="h1">AIFindr</h1>
                <div class="nav">
                     <a class="a4 nave" href="home.html">Home</a>
                    <a class="a2 nave" href="categories.html">Categories</a>
                    <a class="a3 nave" href="submit.html">Submit Tool</a>
                    <a href="user.html"><i class="fa-solid fa-circle-user"></i></a>
                </div>
        </div>
  </header>
  <div class="container">
    <h2>Suggest a New AI Tool</h2>

    <?php if ($success): ?>
      <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="post">
      <label for="name">Tool Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="description">Description:</label>
      <textarea id="description" name="description" rows="4" required></textarea>

      <label for="link">Tool Link:</label>
      <input type="url" id="link" name="link" required>

      <button type="submit">Submit Suggestion</button>
    </form>
  </div>
</body>
</html>
