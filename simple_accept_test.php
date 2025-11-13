<?php
/**
 * Simple test for accepting applications without complex dependencies
 * Use this to test if the basic functionality works
 */

session_start();
if (!isset($_SESSION["admin"])) {
    die("Please log in as admin first");
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method");
}

$action = $_POST['action'] ?? '';
$memberId = $_POST['member_id'] ?? 0;

if (empty($action) || empty($memberId)) {
    die("Missing required parameters");
}

if ($action !== 'accept') {
    die("Invalid action");
}

try {
    // Just update the database status - no email, no Google Sheets
    require_once 'Database/db.php';
    $database = new Database();
    $pdo = $database->createConnection();

    // Get member details
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ? AND membership_status = 'New_member'");
    $stmt->execute([$memberId]);
    $member = $stmt->fetch();

    if (!$member) {
        header("Location: pending_applications.php?error=" . urlencode('Member not found or already processed'));
        exit();
    }

    // Update member status to Accepted
    $updateStmt = $pdo->prepare("UPDATE members SET membership_status = 'Accepted', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $updateResult = $updateStmt->execute([$memberId]);

    if ($updateResult) {
        header("Location: pending_applications.php?success=" . urlencode('Member accepted successfully (simple test - no email sent)'));
    } else {
        header("Location: pending_applications.php?error=" . urlencode('Failed to update member status in database'));
    }
} catch (Exception $e) {
    header("Location: pending_applications.php?error=" . urlencode('Server error: ' . $e->getMessage()));
}
?>
