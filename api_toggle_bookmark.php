<?php
session_start();
require_once 'conn.php'; 

// Set the header for a JSON response
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'action' => ''];

// 1. Validation Checks
if (!isset($_SESSION['username'])) {
    $response['message'] = 'User is not logged in.';
    http_response_code(401);
    echo json_encode($response);
    exit;
}

if (!isset($_POST['toolid']) || !is_numeric($_POST['toolid'])) {
    $response['message'] = 'Invalid tool ID.';
    echo json_encode($response);
    exit;
}

$current_username = $_SESSION['username'];
$toolid = intval($_POST['toolid']);

try {
    // Check if the tool is already saved (bookmarked)
    $sql_check = "SELECT id FROM usersavedtools WHERE username = ? AND toolid = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('si', $current_username, $toolid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // --- A. Tool is already saved: DELETE (Unbookmark) ---
        $sql_action = "DELETE FROM usersavedtools WHERE username = ? AND toolid = ?";
        $action_type = 'removed';
    } else {
        // --- B. Tool is NOT saved: INSERT (Bookmark) ---
        $sql_action = "INSERT INTO usersavedtools (username, toolid) VALUES (?, ?)";
        $action_type = 'saved';
    }
    $stmt_check->close();

    // Execute the action (INSERT or DELETE)
    $stmt_action = $conn->prepare($sql_action);
    $stmt_action->bind_param('si', $current_username, $toolid);
    $stmt_action->execute();
    $stmt_action->close();

    $response['success'] = true;
    $response['action'] = $action_type;
    $response['message'] = "Tool successfully " . $action_type . ".";

} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
exit;
?>