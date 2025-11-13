<?php
/**
 * Test script for Google Sheets Integration
 * This script helps test the Google Sheets functionality without going through the full application flow
 */

// Include configuration first
require_once 'config/google_sheets_config.php';
require_once 'Action/google_sheets_integration.php';

// Sample member data for testing
$testMember = array(
    'full_name' => 'Test User',
    'university_id' => '12345678',
    'email' => 'test@student.bracu.ac.bd',
    'gsuite_email' => 'test.user@g.bracu.ac.bd',
    'department' => 'Computer Science and Engineering',
    'phone' => '+8801234567890',
    'facebook_url' => 'https://facebook.com/testuser',
    'firstPriority' => 'Cultural Events',
    'secondPriority' => 'Music'
);

echo "<h1>Google Sheets Integration Test</h1>";
echo "<h2>Test Member Data:</h2>";
echo "<pre>" . print_r($testMember, true) . "</pre>";

echo "<h2>Testing Google Sheets Integration...</h2>";

$result = sendToGoogleSheets($testMember);

echo "<h3>Result:</h3>";
echo "<pre>" . print_r($result, true) . "</pre>";

if ($result['success']) {
    echo "<p style='color: green; font-weight: bold;'>✅ SUCCESS: Data sent to Google Sheets successfully!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ FAILED: " . htmlspecialchars($result['message']) . "</p>";
}

// Also log the test
logGoogleSheetsOperation('test_integration', $testMember, $result);

echo "<h3>Test logged to: logs/google_sheets.log</h3>";

?>
