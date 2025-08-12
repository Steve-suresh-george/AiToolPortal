<?php
session_start(); // Start the session to access logged-in user info
include 'conn.php'; // DB connection file


$sql = "SELECT name, description, websitelink 
        FROM suggestions 
        WHERE username = ?"; // Fetch only for the logged-in user

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
    <h1 class="text-center mb-5">Your Suggested Tools</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row g-4">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm text-dark">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($row['description']) . '</p>
                        <p class="card-text">' . htmlspecialchars($row['username']) . '</p>
                        <p class="card-text">
                            <a href="' . htmlspecialchars($row['websitelink']) . '">' . htmlspecialchars($row['websitelink']) . '</a>
                        </p>
                    </div>
                </div>
            </div>';
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning text-center">You haven\'t suggested any tools yet.</div>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</div>
</body>
</html>
