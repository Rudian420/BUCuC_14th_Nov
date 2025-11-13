<?php
// Simple test script to verify Excel export functionality
session_start();

// Simulate admin session for testing
$_SESSION["admin"] = true;
$_SESSION["admin_name"] = "Test Admin";

echo "<h2>Testing Excel Export Functionality</h2>";

// Check if PHPSpreadsheet is available
if (file_exists('vendor/autoload.php')) {
    echo "<p style='color: green;'>✓ PHPSpreadsheet library found</p>";
} else {
    echo "<p style='color: red;'>✗ PHPSpreadsheet library not found</p>";
    exit;
}

// Check if database connection works
try {
    require_once 'Database/db.php';
    $database = new Database();
    $pdo = $database->createConnection();
    echo "<p style='color: green;'>✓ Database connection successful</p>";

    // Check if members table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM members");
    $result = $stmt->fetch();
    $memberCount = $result['count'];

    echo "<p style='color: green;'>✓ Members table found with {$memberCount} records</p>";

    if ($memberCount > 0) {
        echo "<p style='color: green;'>✓ Data available for export</p>";
        echo "<p><a href='Action/export_members_excel.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Excel Export</a></p>";
    } else {
        echo "<p style='color: orange;'>⚠ No member data found in database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='pending_applications.php'>← Back to Pending Applications</a></p>";
