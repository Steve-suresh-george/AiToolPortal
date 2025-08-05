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
    $toolStmt = $conn->prepare("SELECT t.toolid, t.name, t.description, t.pricing, t.websitelink, c.name as category_name 
                                FROM tools t
                                JOIN categories c ON t.categoryid = c.categoryid 
                                WHERE t.name LIKE ?");
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
                        <a class="nav-link active" href="search.php">Search</a>
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

    <!-- Search Section -->
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="search.php" method="get" class="mb-5">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control search-box" name="q" 
                               placeholder="Search for AI tools..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn search-button text-white px-4" type="submit">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>

                <!-- Results Section -->
                <?php if ($search !== '' && $results && $results->num_rows > 0): ?>
                    <div class="row g-4">
                        <?php while ($row = $results->fetch_assoc()): ?>
                            <div class="col-md-6">
                                <div class="tool-card rounded-4 p-4 h-100 text-white">
                                    <h3 class="h4 mb-3"><?php echo htmlspecialchars($row['name']); ?></h3>
                                    
                                    <span class="badge category-badge mb-3">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </span>
                                    
                                    <p class="mb-3">
                                        <small class="text-primary fw-bold">Description</small><br>
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </p>
                                    
                                    <p class="mb-4">
                                        <small class="text-primary fw-bold">Pricing</small><br>
                                        <?php echo htmlspecialchars($row['pricing']); ?>
                                    </p>
                                    
                                    <a href="<?php echo htmlspecialchars($row['websitelink']); ?>" 
                                       class="btn visit-btn text-white w-100" target="_blank">
                                        <i class="fas fa-external-link-alt me-2"></i>Visit Website
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php elseif ($search !== ''): ?>
                    <div class="no-results text-center p-5 rounded-4 text-white">
                        <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                        <h3 class="h4">No Results Found</h3>
                        <p class="mb-0">
                            No tools found for "<strong><?php echo htmlspecialchars($search); ?></strong>"
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
