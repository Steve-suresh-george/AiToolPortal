<?php
session_start();
// Lightweight endpoint to record when a user clicks Visit.
// Expects POST with 'toolid' and optional 'url'. Requires user to be logged in.

require_once 'conn.php';

header('Content-Type: application/json');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$toolid = isset($_POST['toolid']) ? intval($_POST['toolid']) : 0;
$url = isset($_POST['url']) ? trim($_POST['url']) : '';

if (!$toolid) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing toolid']);
    exit;
}

if (!$username) {
    // Not logged in - return 401 so client can handle it if needed
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    // Use SELECT then INSERT/UPDATE to avoid relying on unique keys
    $check = $conn->prepare('SELECT COUNT(*) AS cnt FROM userrecenttools WHERE username = ? AND toolid = ?');
    $check->bind_param('si', $username, $toolid);
    $check->execute();
    $res = $check->get_result();
    $row = $res->fetch_assoc();
    $count = isset($row['cnt']) ? intval($row['cnt']) : 0;
    $check->close();

    if ($count > 0) {
        $stmt = $conn->prepare('UPDATE userrecenttools SET lastusedat = NOW() WHERE username = ? AND toolid = ?');
        $stmt->bind_param('si', $username, $toolid);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare('INSERT INTO userrecenttools (username, toolid, lastusedat) VALUES (?, ?, NOW())');
        $stmt->bind_param('si', $username, $toolid);
        $stmt->execute();
        $stmt->close();
    }

    // Optionally you can log the url somewhere if you want; ignoring for now.

    echo json_encode(['success' => true]);
    exit;
} catch (Exception $e) {
    error_log('api_record_visit error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}

?>
