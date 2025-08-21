<?php
session_start();

// Include your database connection file
require_once 'conn.php';

// Check if the user is authenticated and has the correct role
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Fetch the role of the current user
$current_username = $_SESSION['username'];
$role_sql = "SELECT role FROM users WHERE username = ?";
$stmt = $conn->prepare($role_sql);
$stmt->bind_param('s', $current_username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Assuming role '1' is admin. Redirect if not an admin.
if (!isset($user_data['role']) || $user_data['role'] !== 1) {
    header('Location: user.php');
    exit;
}

// --- User Deletion Logic ---
if (isset($_GET['delete_user_username'])) {
    $userUsername = $_GET['delete_user_username'];
    
    // Prevent admin from deleting themselves
    if ($userUsername === $current_username) {
        die("You cannot delete your own account from this page.");
    }
    
    $deleteSql = "DELETE FROM users WHERE username = ?";
    
    try {
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param('s', $userUsername);
        $stmt->execute();
        
        header('Location: admin.php');
        exit;
    } catch (Exception $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}

// --- Fetch all user details excluding admins ---
$userQuery = "SELECT username FROM users WHERE role != 1";
$users = [];
try {
    $userResult = $conn->query($userQuery);
    if ($userResult) {
        $users = $userResult->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    die("Could not fetch user details: " . $e->getMessage());
}

// --- Fetch all feedback from all users ---
$feedbackQuery = "SELECT t.name AS toolname, f.username, f.comment, f.createdat
                  FROM feedback AS f
                  JOIN tools AS t ON f.toolid = t.toolid
                  ORDER BY f.createdat DESC";
$feedbackResult = $conn->query($feedbackQuery);

// --- Fetch all suggestions from all users ---
$suggestionsQuery = "SELECT name, description, websitelink, submitted_by
                     FROM suggestions";
$suggestionsResult = $conn->query($suggestionsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
            color: white;
        }
        .table-dark {
            --bs-table-bg: rgba(255, 255, 255, 0.05);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.15);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .section-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .card-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-dark text-white">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container my-5">
        <h2 class="mb-4 text-center section-title">Admin Dashboard</h2>
        
        <h3 class="mt-5 mb-3">User Management</h3>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td>
                                    <a href="?delete_user_username=<?php echo htmlspecialchars($user['username']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center text-muted">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <h3 class="mt-5 mb-3">All User Feedback</h3>
        <div class="row g-4">
            <?php if ($feedbackResult && mysqli_num_rows($feedbackResult) > 0): ?>
                <?php while ($row = $feedbackResult->fetch_assoc()): ?>
                    <div class="col-12">
                        <div class="card card-custom p-4 rounded-4 text-white">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-0 text-primary"><?php echo htmlspecialchars($row['toolname']); ?></h5>
                                    <small class="text-muted">By: <?php echo htmlspecialchars($row['username']); ?></small>
                                </div>
                                <small class="text-muted"><?php echo date("F j, Y", strtotime($row['created_at'])); ?></small>
                            </div>
                            <p class="mb-0"><?php echo htmlspecialchars($row['comment']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <p>No feedback submitted yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <hr>

        <h3 class="mt-5 mb-3">All Tool Suggestions</h3>
        <div class="row g-4">
            <?php if ($suggestionsResult && mysqli_num_rows($suggestionsResult) > 0): ?>
                <?php while ($row = $suggestionsResult->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-custom h-100 p-4 rounded-4 text-white">
                            <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text mb-2"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text mb-1"><a href="<?php echo htmlspecialchars($row['websitelink']); ?>" target="_blank" class="text-info">Visit Site</a></p>
                            <small class="text-muted">Submitted by: <?php echo htmlspecialchars($row['submitted_by']); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <p>No tools have been suggested yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>