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

if (!isset($_POST['suggestion_id']) || !is_numeric($_POST['suggestion_id'])) {
    $response['message'] = 'Invalid suggestion ID.';
    echo json_encode($response);
    exit;
}

$current_username = $_SESSION['username'];
$suggestion_id = intval($_POST['suggestion_id']);

// Use the mysqli connection object
if (!$conn) {
    $response['message'] = 'Database connection failed.';
    echo json_encode($response);
    exit;
}

try {
    // 2. Prepare the DELETE statement
    // IMPORTANT: Use the 'id' and 'submitted_by' (username) to ensure security.
    $sql_delete = "DELETE FROM suggestions WHERE id = ? AND submitted_by = ?";
    
    $stmt = $conn->prepare($sql_delete);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // 'i' for ID (int), 's' for username (string)
    $stmt->bind_param('is', $suggestion_id, $current_username); 
    $stmt->execute();
    
    // 3. Check if any rows were deleted
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Suggestion successfully deleted.';
    } else {
        $response['message'] = 'Deletion failed. Suggestion ID not found or not owned by user.';
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Internal Server Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
exit;
?>