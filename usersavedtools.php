<?php
session_start();
// 1. Authentication Check
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'conn.php'; 

$current_username = $_SESSION['username'];
$saved_tools = [];

// 2. Database Query: Fetch all saved tools for the current user
$sql = "SELECT t.toolid, t.name, t.description, t.pricing, t.websitelink, uts.savedat
        FROM usersavedtools uts
        JOIN tools t ON uts.toolid = t.toolid
        WHERE uts.username = ?
        ORDER BY uts.savedat DESC";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('s', $current_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $saved_tools[] = $row;
        }
    }
    $stmt->close();
} else {
    // Handle prepare error if needed
    // die("SQL prepare failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Tools - AIFindr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <style>
        /* Basic Dark Theme styles to match your dashboard */
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            color: white;
            min-height: 100vh;
        }
        .container {
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .tool-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
        }
        .tool-name {
            color: #4db8ff; /* A bright color for tool names */
        }
        /* Style for the pricing badge */
        .badge-pricing {
            font-size: 0.8em;
            padding: 0.4em 0.7em;
            border-radius: 50rem;
            display: inline-block;
            margin-right: 10px;
            text-transform: capitalize;
        }
        .badge-free { background-color: #28a745; }
        .badge-freemium { background-color: #ffc107; color: #343a40; }
        .badge-paid { background-color: #dc3545; }
        .visit-btn {
            background-color: #007bff;
            border: none;
        }
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: linear-gradient(135deg, #4285f4, #34a853);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">
        <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav align-items-center ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">Search</a>
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
    <div class="container">
        <h1 class="mb-4"><i class="fas fa-bookmark me-3"></i>Your Saved Tools</h1>
        
        <p class="text-muted"><a href="user.php" class="text-white text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a></p>
        
        <hr class="text-white-50 mb-5">

        <div class="row">
            <?php if (count($saved_tools) > 0): ?>
                <?php foreach ($saved_tools as $tool): ?>
                    <div class="col-lg-6">
                        <div class="tool-card shadow">
                            <h2 class="h4 tool-name"><?php echo htmlspecialchars($tool['name']); ?></h2>
                            
                            <?php 
                                $pricing_class = 'badge-secondary';
                                switch ($tool['pricing']) {
                                    case 'free': $pricing_class = 'badge-free'; break;
                                    case 'freemium': $pricing_class = 'badge-freemium'; break;
                                    case 'paid': $pricing_class = 'badge-paid'; break;
                                }
                            ?>
                            <span class="badge badge-pricing <?php echo $pricing_class; ?>">
                                <?php echo htmlspecialchars($tool['pricing']); ?>
                            </span>
                            
                            <p class="mt-3"><?php echo htmlspecialchars($tool['description']); ?></p>
                            <p class="mt-3">Saved on: <?php echo date("F j, Y", strtotime($tool['savedat'])); ?></p>
                            
                            <a href="#" 
                               class="btn btn-primary visit-btn" 
                               data-tool-id="<?php echo htmlspecialchars($tool['toolid']); ?>"
                               data-tool-url="<?php echo htmlspecialchars($tool['websitelink']); ?>"
                               onclick="recordAndVisit(this); return false;">
                                <i class="fas fa-external-link-alt me-2"></i>Visit Tool
                            </a>

                            <button type="button" id="unsave-btn-<?php echo htmlspecialchars($tool['toolid']); ?>" class="btn btn-danger ms-2 unsave-tool-btn" data-tool-id="<?php echo htmlspecialchars($tool['toolid']); ?>" onclick="toggleBookmark(this, true);">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        You haven't saved any tools yet! Visit the <a href="categories.php" class="alert-link">Categories page</a> to find some.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // NOTE: You must copy your working recordAndVisit() and toggleBookmark() 
        // functions from categories1.php into this file (or an external JS file) 
        // for the buttons to work.
        
        /**
         * The recordAndVisit function for the "Visit Tool" button.
         * (Copy the body of the function from your categories1.php)
         */
        function recordAndVisit(element) {
            // ... Your working recordAndVisit function body here ...
            // The logic should look something like this:
            const toolId = element.getAttribute('data-tool-id');
            const toolUrl = element.getAttribute('data-tool-url');
            if (!toolId || !toolUrl) {
                alert("Tool data is incomplete.");
                return;
            }
            
            fetch('api_record_visit.php?toolid=' + toolId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.tool_url;
                    } else {
                        alert("An error occurred while logging the visit. Redirecting anyway.");
                        window.location.href = toolUrl;
                    }
                })
                .catch(error => {
                    alert("Network error. Redirecting to the tool now.");
                    window.location.href = toolUrl;
                });
        }
        
        /**
         * The toggleBookmark function, adapted for the "Remove" button.
         * (Copy the body of the function from your categories1.php)
         * * @param {HTMLElement} element - The button element that was clicked.
         * @param {boolean} isRemoveAction - Flag to indicate we expect a REMOVE action.
         */
        function toggleBookmark(element, isRemoveAction = false) {
            const toolId = element.getAttribute('data-tool-id');
            
            if (!toolId) {
                console.error("Missing tool ID for bookmark.");
                return;
            }

            const originalHtml = element.innerHTML;
            element.disabled = true;
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            const formData = new FormData();
            formData.append('toolid', toolId);

            fetch('api_toggle_bookmark.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.status === 401) {
                    alert('You must be logged in to modify saved tools.');
                    element.disabled = false;
                    element.innerHTML = originalHtml;
                    return { success: false, message: 'Not logged in' }; 
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.action === 'removed') {
                        // If successfully removed, hide the tool card from the list
                        if (isRemoveAction) {
                            const card = element.closest('.col-lg-6');
                            if (card) {
                                card.remove();
                            }
                        } else {
                            // Logic for an 'unsaved' button click (less relevant here, but good practice)
                            element.classList.remove('btn-success');
                            element.classList.add('btn-outline-light');
                            element.innerHTML = '<i class="far fa-bookmark"></i> Save Tool';
                        }
                    } else if (data.action === 'saved') {
                        // Logic for an 'saved' button click (shouldn't happen on this page)
                        alert("Tool saved successfully! Refreshing page is recommended.");
                    }
                } else {
                    alert("Could not update bookmark status: " + data.message);
                    element.disabled = false;
                    element.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                console.error("Network error toggling bookmark:", error);
                alert("A network error occurred.");
                element.disabled = false;
                element.innerHTML = originalHtml;
            });
        }
    </script>
</body>
</html>