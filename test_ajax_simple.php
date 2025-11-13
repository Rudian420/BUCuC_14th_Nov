<?php
/**
 * Simple AJAX test endpoint
 * This will help debug the accept button issue
 */

session_start();
if (!isset($_SESSION["admin"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Set content type for JSON response
header('Content-Type: application/json');

// Get POST data
$action = $_POST['action'] ?? '';
$memberId = $_POST['member_id'] ?? 0;

echo json_encode([
    'success' => true,
    'message' => 'Test successful',
    'action' => $action,
    'member_id' => $memberId,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
