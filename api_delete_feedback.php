<?php
session_start();
require_once 'conn.php'; 

// Set the header for a JSON response
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// 1. Validation Checks
if (!isset($_SESSION['username'])) {
    $response['message'] = 'User is not logged in.';
    http_response_code(401);
    echo json_encode($response);
    exit;
}

// NOTE: We need a unique ID for the feedback entry. 
// Assuming your feedback table has an `id` column.
if (!isset($_POST['feedback_id']) || !is_numeric($_POST['feedback_id'])) {
    $response['message'] = 'Invalid feedback ID.';
    echo json_encode($response);
    exit;
}

$current_username = $_SESSION['username'];
$feedback_id = intval($_POST['feedback_id']);

try {
    // 2. Prepare the DELETE statement
    // IMPORTANT: We use both the ID and the username to ensure a user can only delete their own entry.
    // Assuming the `feedback` table has columns: `id` and `username`.
    $sql_delete = "DELETE FROM feedback WHERE id = ? AND username = ?";
    
    $stmt = $conn->prepare($sql_delete);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('is', $feedback_id, $current_username); // 'i' for ID (int), 's' for username (string)
    $stmt->execute();
    
    // 3. Check if any rows were deleted
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Feedback successfully deleted.';
    } else {
        // This could mean the ID was wrong or the user didn't own the entry.
        $response['message'] = 'Deletion failed. Feedback ID not found or not owned by user.';
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Internal Server Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
exit;
?>