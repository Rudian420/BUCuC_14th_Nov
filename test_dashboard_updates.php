<?php
// Test script to verify dashboard update functionality
session_start();
require_once 'Database/db.php';

// Mock admin session for testing
$_SESSION["admin"] = true;
$_SESSION["admin_id"] = 1;

$database = new Database();
$conn = $database->createConnection();

echo "<h1>Dashboard Update Test</h1>\n";

// Test getCurrentStats function
echo "<h2>Testing getCurrentStats</h2>\n";
$_POST['action'] = 'get_current_stats';

ob_start();
include 'Action/dashboard_update_management.php';
$output = ob_get_clean();

echo "<pre>Response: " . htmlspecialchars($output) . "</pre>\n";

$data = json_decode($output, true);
if ($data && $data['success']) {
    echo "<p style='color: green;'>✓ getCurrentStats working correctly</p>\n";
    echo "<p>Total Members: " . ($data['data']['members']['total_members'] ?? 'N/A') . "</p>\n";
    echo "<p>Pending Applications: " . ($data['data']['members']['pending_applications'] ?? 'N/A') . "</p>\n";
    echo "<p>Gender Distribution: M:" . ($data['data']['members']['total_males'] ?? 0) . 
         " F:" . ($data['data']['members']['total_females'] ?? 0) . 
         " O:" . ($data['data']['members']['total_others'] ?? 0) . "</p>\n";
    echo "<p>Categories: " . count($data['data']['categories'] ?? []) . "</p>\n";
} else {
    echo "<p style='color: red;'>✗ getCurrentStats failed: " . ($data['message'] ?? 'Unknown error') . "</p>\n";
}

// Test database connection
echo "<h2>Testing Database Connection</h2>\n";
try {
    $testSql = "SELECT COUNT(*) as member_count FROM members";
    $testStmt = $conn->prepare($testSql);
    $testStmt->execute();
    $result = $testStmt->fetch();
    echo "<p style='color: green;'>✓ Database connection working</p>\n";
    echo "<p>Total rows in members table: " . $result['member_count'] . "</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>\n";
}

// Test table structure
echo "<h2>Testing Table Structure</h2>\n";
try {
    $structureSql = "DESCRIBE members";
    $structureStmt = $conn->prepare($structureSql);
    $structureStmt->execute();
    $columns = $structureStmt->fetchAll();
    echo "<p style='color: green;'>✓ Members table exists with " . count($columns) . " columns</p>\n";
    
    $requiredColumns = ['id', 'full_name', 'gender', 'event_category', 'application_status', 'status'];
    $existingColumns = array_column($columns, 'Field');
    $missingColumns = array_diff($requiredColumns, $existingColumns);
    
    if (empty($missingColumns)) {
        echo "<p style='color: green;'>✓ All required columns exist</p>\n";
    } else {
        echo "<p style='color: orange;'>⚠ Missing columns: " . implode(', ', $missingColumns) . "</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Table structure check failed: " . $e->getMessage() . "</p>\n";
}

echo "<h2>Test Completed</h2>\n";
echo "<p><a href='admin_dashboard.php'>Return to Admin Dashboard</a></p>\n";
?>