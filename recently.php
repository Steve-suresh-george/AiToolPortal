<?php
session_start();
require_once 'conn.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Get the tool ID from the URL parameter
if (!isset($_GET['toolid']) || !is_numeric($_GET['toolid'])) {
    // Redirect to the home page or an error page if the tool ID is missing or invalid
    header("Location: home.php");
    exit;
}

$current_username = $_SESSION['username'];
$toolid = $_GET['toolid'];

// --- Step 1: Record the tool usage in the database ---
// SQL query to insert or update the tool usage record
$sql_record_visit = "INSERT INTO userrecenttools (username, toolid, lastusedat) 
                     VALUES (?, ?, NOW()) 
                     ON DUPLICATE KEY UPDATE lastusedat = NOW()";

try {
    $stmt_record = $conn->prepare($sql_record_visit);
    $stmt_record->bind_param('si', $current_username, $toolid);
    $stmt_record->execute();
    $stmt_record->close();
} catch (Exception $e) {
    // You can log this error for debugging
    error_log("Error recording tool visit: " . $e->getMessage());
}

// --- Step 2: Retrieve the tool URL from the database ---
$sql_get_url = "SELECT websitelink FROM tools WHERE toolid = ?";
$tool_url = '';

try {
    $stmt_url = $conn->prepare($sql_get_url);
    $stmt_url->bind_param('i', $toolid);
    $stmt_url->execute();
    $result = $stmt_url->get_result();

    if ($row = $result->fetch_assoc()) {
        $tool_url = $row['websitelink'];
    }
    $stmt_url->close();
} catch (Exception $e) {
    // Log any errors
    error_log("Error fetching tool URL: " . $e->getMessage());
}

$conn->close();

// --- Step 3: Redirect the user to the tool's URL ---
if ($tool_url) {
    header("Location: " . $tool_url);
} else {
    // Redirect to an error page or the homepage if the URL is not found
    header("Location: home.php");
}
exit;
?>