<?php
include 'conn.php'; // DB connection file

$sql = "SELECT name, description, websitelink FROM suggestions";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Suggestions</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary bg-gradient text-white min-vh-100">

  <div class="container py-5">
    <h1 class="text-center mb-5">User-Suggested Tools</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
      echo '<div class="row g-4">';
      while($row = mysqli_fetch_assoc($result)) {
        echo '
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm text-dark">
              <div class="card-body">
                <h5 class="card-title">'. htmlspecialchars($row['name']) .'</h5>
                <p class="card-text">'. htmlspecialchars($row['description']) .'</p>
                <p class="card-text">'. htmlspecialchars($row['websitelink']) .'</p>
              </div>
            </div>
          </div>';
      }
      echo '</div>';
    } else {
      echo '<div class="alert alert-warning text-center">No suggestions available.</div>';
    }

    mysqli_close($conn);
    ?>
  </div>

</body>
</html>
