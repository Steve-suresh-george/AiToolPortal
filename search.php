<?php
include 'conn.php'; // Include your database connection

// --- NEW FUNCTION: Highlight Search Term ---
function highlightSearchTerm($text, $searchTerm) {
    if (empty($searchTerm)) {
        return $text;
    }
    // Use str_ireplace for case-insensitive replacement
    // Wrap the matched term with <mark> tags
    return str_ireplace($searchTerm, '<mark>' . htmlspecialchars($searchTerm) . '</mark>', $text);
}
// ------------------------------------------

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = null; // Initialize $results to null
$trendingCategories = []; // Initialize the variable as an empty array

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = null; // Initialize $results to null
$trendingCategories = []; // FIX: Initialize the variable as an empty array

// --- 1. Fetch Trending Categories (Always run this, but check connection) ---
if (isset($conn) && $conn->connect_error == null) {
    // Select up to 4 random categories to suggest
    $trendStmt = $conn->prepare("SELECT name FROM categories ORDER BY RAND() LIMIT 4");
    
    if ($trendStmt) {
        $trendStmt->execute();
        $trendResult = $trendStmt->get_result();

        while ($row = $trendResult->fetch_assoc()) {
            $trendingCategories[] = htmlspecialchars($row['name']);
        }
        $trendStmt->close();
    }
}
// --------------------------------------------------------------------------

// --- 2. Handle Search Query ---
if ($search !== '') {
    // A. Check if search matches a category name (Redirect)
    $catStmt = $conn->prepare("SELECT categoryid FROM categories WHERE name = ?");
    $catStmt->bind_param("s", $search);
    $catStmt->execute();
    $catResult = $catStmt->get_result();

    if ($catResult->num_rows > 0) {
        $category = $catResult->fetch_assoc();
        // Close DB connection before redirect
        $conn->close(); 
        header("Location: categories1.php?id=" . $category['categoryid']);
        exit();
    }
    $catStmt->close();

    // B. Search for tools by tool name/description
    $toolStmt = $conn->prepare("SELECT t.toolid, t.name, t.description, t.pricing, t.websitelink, c.name as category_name 
                                 FROM tools t
                                 JOIN categories c ON t.categoryid = c.categoryid 
                                 WHERE t.name LIKE ? OR t.description LIKE ?");
    $param = "%$search%"; // Search anywhere in the string
    $toolStmt->bind_param("ss", $param, $param);
    $toolStmt->execute();
    $results = $toolStmt->get_result();
    $toolStmt->close();
}

// Close the main connection only once, after all DB operations.
$conn->close(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search AI Tools - AIFindr</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
        }
        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #4285f4;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
            color: white;
        }
        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .search-button {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: all 0.3s ease;
        }
        .search-button:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
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
        .category-badge {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
        }
        .visit-btn {
            background: linear-gradient(135deg, #4285f4, #34a853);
            border: none;
            transition: all 0.3s ease;
        }
        .visit-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3367d6, #2d904d);
        }
        .no-results {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
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
<body class="bg-dark">
    <!-- Navbar -->
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
                        <a class="nav-link active" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="suggest.php">Submit Tool</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h1 class="text-white text-center mb-4 pt-3">Find the Perfect AI Tool</h1>
                
                <form action="search.php" method="get" class="mb-5">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control search-box" name="q" 
                               placeholder="Search by tool name or keyword..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn search-button text-white px-4" type="submit">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>

                <?php if ($search === ''): ?>
                    <div class="text-white text-center p-4 rounded-4 bg-transparent">
                        <h4 class="h5 mb-3 text-white-50">Trending Categories</h4>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <?php foreach ($trendingCategories as $cat): ?>
                                <a href="search.php?q=<?php echo urlencode($cat); ?>" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-tag me-1"></i> <?php echo $cat; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <hr class="text-white-50 my-4">
                        <p class="text-muted mb-0">Start typing a name or keyword above to explore hundreds of tools!</p>
                    </div>
                <?php endif; ?>

                <?php if ($search !== '' && $results && $results->num_rows > 0): ?>
    <h2 class="h5 text-white mb-4">Showing **<?php echo $results->num_rows; ?>** results for "<?php echo htmlspecialchars($search); ?>"</h2>
    <div class="row g-4">
        <?php while ($row = $results->fetch_assoc()): ?>
            <div class="col-md-6">
                <div class="tool-card rounded-4 p-4 h-100 text-white position-relative">
                    <?php if (isset($_SESSION['username'])): // Only show if user is logged in ?>
                        <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 mt-3 me-3" 
                                onclick="toggleBookmark(this, <?php echo $row['toolid']; ?>)">
                            <i class="far fa-bookmark"></i>
                        </button>
                    <?php endif; ?>

                    <h3 class="h4 mb-3">
                        <?php echo highlightSearchTerm(htmlspecialchars($row['name']), $search); ?>
                    </h3>
                    
                    <span class="badge category-badge mb-3">
                        <i class="fas fa-folder me-1"></i> <?php echo htmlspecialchars($row['category_name']); ?>
                    </span>
                    
                    <p class="mb-3">
                        <small class="text-info fw-bold">Description</small><br>
                        <?php echo highlightSearchTerm(htmlspecialchars($row['description']), $search); ?>
                    </p>
                    
                    <p class="mb-4">
                        <small class="text-info fw-bold">Pricing</small><br>
                        <?php 
                            $pricing = htmlspecialchars($row['pricing']);
                            if ($pricing == 'Free') {
                                echo '<i class="fas fa-gift text-success me-1"></i> Free';
                            } elseif ($pricing == 'Freemium') {
                                echo '<i class="fas fa-hand-holding-dollar text-warning me-1"></i> Freemium';
                            } elseif ($pricing == 'Paid') {
                                echo '<i class="fas fa-credit-card text-danger me-1"></i> Paid';
                            } else {
                                echo $pricing; // Fallback for other values
                            }
                        ?>
                    </p>
                    
                    <a href="<?php echo htmlspecialchars($row['websitelink']); ?>" 
                       class="btn visit-btn text-white w-100" target="_blank"
                       onclick="recordAndVisit(<?php echo $row['toolid']; ?>, '<?php echo htmlspecialchars($row['websitelink']); ?>'); return false;">
                        <i class="fas fa-external-link-alt me-2"></i>Visit Website
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php elseif ($search !== ''): ?>
    <div class="no-results text-center p-5 rounded-4 text-white">
        <i class="fas fa-frown fa-3x mb-3 text-secondary"></i>
        <h3 class="h4">Oops! No Tools Found</h3>
        <p class="mb-3">
            We couldn't find any tools matching "<strong><?php echo htmlspecialchars($search); ?></strong>".
            <br>Try refining your search or explore existing categories.
        </p>
        <p>You can also:</p>
        <a href="categories.php" class="btn btn-outline-primary me-2"><i class="fas fa-list"></i> Browse Categories</a>
        <a href="suggest.php" class="btn btn-outline-success"><i class="fas fa-plus-circle"></i> Suggest a New Tool</a>
    </div>
<?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // --- JavaScript for Bookmark Button (requires session_start() at PHP top) ---
    function toggleBookmark(button, toolId) {
        // You'll need to implement the actual AJAX call to add/remove bookmark
        // For now, this just changes the icon to simulate a toggle
        const icon = button.querySelector('i');
        if (icon.classList.contains('far')) { // Currently not bookmarked (outline)
            icon.classList.remove('far');
            icon.classList.add('fas'); // Change to solid icon
            // TODO: Send AJAX request to api_add_bookmark.php with toolId
            console.log("Bookmarked tool:", toolId);
            alert("Tool bookmarked!");
        } else { // Currently bookmarked (solid)
            icon.classList.remove('fas');
            icon.classList.add('far'); // Change to outline icon
            // TODO: Send AJAX request to api_remove_bookmark.php with toolId
            console.log("Unbookmarked tool:", toolId);
            alert("Tool unbookmarked!");
        }
    }

    // --- JavaScript for Recording Recent Usage (from categories1.php) ---
    function recordAndVisit(toolId, websiteLink) {
        // Only log if the user is logged in (optional, but good for data cleanliness)
        // You'd need a way to know if $_SESSION['username'] exists in JS or pass it.
        // For simplicity, we'll assume the PHP 'recently.php' handles session checks.
        
        const logUrl = 'recently.php?toolid=' + toolId;
        
        fetch(logUrl, {
            method: 'GET' // Or POST, if your recently.php expects POST
        })
        .then(response => {
            if (!response.ok) {
                console.error("Failed to log tool usage. Status:", response.status);
            }
            // No need to wait for the log to finish before redirecting
            console.log(`Tool ${toolId} usage logging initiated.`);
        })
        .catch(error => {
            console.error("Error initiating tool usage logging:", error);
        })
        .finally(() => {
            // Always redirect the user to the tool's website
            window.open(websiteLink, '_blank');
        });
    }
</script>
</body>
</html>
