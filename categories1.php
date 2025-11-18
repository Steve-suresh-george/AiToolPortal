<?php
session_start();
include 'conn.php';

// Get category ID from URL
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get category name
$categoryQuery = "SELECT name FROM categories WHERE categoryid = ?";
$stmt = $conn->prepare($categoryQuery);
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$categoryResult = $stmt->get_result();
$categoryName = $categoryResult->num_rows > 0 ? $categoryResult->fetch_assoc()['name'] : 'Unknown Category';
$stmt->close();

// Handle feedback submission
$feedbackSuccess = false;
$feedbackError = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'], $_POST['toolid'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        $feedbackError = 'You must be logged in to submit feedback.';
    } else {
        $feedbackComment = $_POST['feedback'];
        $toolid = intval($_POST['toolid']);
        $username = $_SESSION['username'];
        $rating = 0; // Assuming a default rating for now, you can add a rating input later

        // Prepare the INSERT query to add feedback to the feedback table
        $stmt = $conn->prepare("INSERT INTO feedback (username, toolid, comment) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sis", $username, $toolid, $feedbackComment);
            if ($stmt->execute()) {
                $feedbackSuccess = true;
            } else {
                $feedbackError = 'Failed to submit feedback. Please try again.';
            }
            $stmt->close();
        } else {
            $feedbackError = 'Database error: Unable to prepare statement.';
        }
    }
}

// Get all tools in this category
$sql = "SELECT * FROM tools WHERE categoryid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $categoryName; ?> Tools - AIFindr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .tool-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .category-title {
            background: linear-gradient(135deg, #4285f4, #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .feedback-form textarea {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .feedback-form textarea:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #4285f4;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
            color: white;
        }
        .visit-site-btn {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: transform 0.3s ease;
        }
        .visit-site-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
        }
    </style>
</head>
<body class="bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.html">
                <span class="bg-primary bg-gradient px-3 py-2 rounded-3 fw-bold">AIFindr</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
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

    <div class="container py-5 mt-5">
        <h1 class="display-4 text-center mb-5 fw-bold category-title">
            <?php echo $categoryName; ?> Tools
        </h1>

        <?php if ($feedbackSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>Feedback submitted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($feedbackError): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($feedbackError); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="tool-card rounded-4 p-4 text-white h-100">
                        <h3 class="h4 mb-3"><?php echo htmlspecialchars($row['name']); ?></h3>
                        
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">Description</h6>
                            <p class="text-light mb-3"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">Pricing</h6>
                            <p class="text-light mb-3"><?php echo htmlspecialchars($row['pricing']); ?></p>
                        </div>
        
                        <div class="d-flex gap-2 mb-3">
                            <?php
                                $rawLink = isset($row['websitelink']) ? trim($row['websitelink']) : '';
                                if ($rawLink && !preg_match('#^https?://#i', $rawLink)) {
                                    if (strpos($rawLink, '//') === 0) {
                                        $href = 'https:' . $rawLink;
                                    } else {
                                        $href = 'https://' . ltrim($rawLink, '/');
                                    }
                                } else {
                                    $href = $rawLink;
                                }
                            ?>
                            <a href="<?php echo htmlspecialchars($href ?: '#'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary visit-site-btn flex-grow-1" data-tool-id="<?php echo htmlspecialchars($row['toolid']); ?>" data-tool-url="<?php echo htmlspecialchars($href); ?>" onclick="recordAndVisit(this);">
                                <i class="fas fa-external-link-alt me-2"></i>Visit
                            </a>

                            <button type="button" id="save-btn-<?php echo htmlspecialchars($row['toolid']); ?>" 
                            class="btn btn-outline-light save-tool-btn" data-tool-id="<?php echo htmlspecialchars($row['toolid']); ?>"
                            onclick="toggleBookmark(this);">
                                <i class="far fa-bookmark"></i> Save Tool
                             </button>
                        </div>

                        <form method="POST" class="feedback-form mt-3">
                            <input type="hidden" name="toolid" value="<?php echo htmlspecialchars($row['toolid']); ?>">
                            <div class="form-group">
                                <label class="text-primary mb-2">Your Feedback</label>
                                <textarea name="feedback" class="form-control mb-3" rows="4" 
                                    placeholder="Share your experience with this tool..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    /**
     * Open the tool's website in a new tab and (optionally) record the visit.
     * @param {HTMLElement} el - The anchor element that was clicked.
     * @returns {boolean} - Always returns false to prevent the default anchor action.
     */
    function recordAndVisit(el) {
        var url = (el.getAttribute('data-tool-url') || '').trim();
        var toolId = el.getAttribute('data-tool-id');

        if (!url) {
            // No URL to visit, let the default behavior continue (will likely do nothing)
            return;
        }

        // Prepare analytics data
        try {
            var fd = new FormData();
            if (toolId) fd.append('toolid', toolId);
            fd.append('url', url);

            // Prefer sendBeacon for reliability on navigation; fall back to fetch with keepalive
            if (navigator.sendBeacon) {
                // sendBeacon accepts ArrayBuffer/Blob/FormData; use FormData directly where supported
                try {
                    navigator.sendBeacon('api_record_visit.php', fd);
                } catch (e) {
                    // Some browsers may not support FormData with sendBeacon; fallback to fetch below
                    fetch('api_record_visit.php', { method: 'POST', body: fd, keepalive: true }).catch(function(){});
                }
            } else {
                fetch('api_record_visit.php', { method: 'POST', body: fd, keepalive: true }).catch(function(){});
            }
        } catch (e) {
            // ignore analytics errors
        }

        // Do not prevent the anchor's default navigation; let the href + target handle opening.
        return;
    }

    /**
     * Toggles the bookmark status of a tool via API call.
     * @param {HTMLElement} element - The button element that was clicked.
     */
    function toggleBookmark(element) {
        const toolId = element.getAttribute('data-tool-id');
        
        console.log("Attempting to save tool ID:", toolId);

        if (!toolId) {
            console.error("Missing tool ID for bookmark.");
            return;
        }

        // 1. Get current state and set loading state
        const originalHtml = element.innerHTML;
        element.disabled = true;
        element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

        // 2. Prepare the POST request data
        const formData = new FormData();
        formData.append('toolid', toolId);

        // 3. Call the API endpoint
        fetch('api_toggle_bookmark.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Check for unauthenticated error response (401)
            if (response.status === 401) {
                alert('You must be logged in to save tools.');
                element.disabled = false;
                element.innerHTML = originalHtml;
                return { success: false, message: 'Not logged in' }; // Return error object to prevent further processing
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // 4. Update the button based on the action returned by the server
                if (data.action === 'saved') {
                    element.classList.remove('btn-outline-light');
                    element.classList.add('btn-success');
                    element.innerHTML = '<i class="fas fa-bookmark"></i> Saved!';
                } else if (data.action === 'removed') {
                    element.classList.remove('btn-success');
                    element.classList.add('btn-outline-light');
                    element.innerHTML = '<i class="far fa-bookmark"></i> Save Tool';
                }
                setTimeout(() => {
                    element.disabled = false;
                }, 1000); // Re-enable button after a short delay
            } else {
                // Handle API error
                alert("Could not update bookmark status: " + data.message);
                element.disabled = false;
                element.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            // Handle network failure
            console.error("Network error toggling bookmark:", error);
            alert("A network error occurred.");
            element.disabled = false;
            element.innerHTML = originalHtml;
        });
    }
</script>
</body>
</html>
<?php
$conn->close();
?>