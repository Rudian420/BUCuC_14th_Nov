<?php
/**
 * Diagnostic script for Google Sheets Integration
 * This script helps diagnose Google Apps Script deployment issues
 */

// Include configuration first
require_once 'config/google_sheets_config.php';
require_once 'Action/google_sheets_integration.php';

echo "<h1>Google Sheets Integration Diagnostics</h1>";

echo "<h2>1. Configuration Check</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>WebApp URL</td><td>" . GOOGLE_SHEETS_WEBAPP_URL . "</td></tr>";
echo "<tr><td>Timeout</td><td>" . GOOGLE_SHEETS_TIMEOUT . " seconds</td></tr>";
echo "<tr><td>Logging Enabled</td><td>" . (GOOGLE_SHEETS_LOGGING ? 'Yes' : 'No') . "</td></tr>";
echo "</table>";

echo "<h2>2. URL Analysis</h2>";
$url = GOOGLE_SHEETS_WEBAPP_URL;
$parsedUrl = parse_url($url);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Component</th><th>Value</th></tr>";
echo "<tr><td>Host</td><td>" . ($parsedUrl['host'] ?? 'Not found') . "</td></tr>";
echo "<tr><td>Path</td><td>" . ($parsedUrl['path'] ?? 'Not found') . "</td></tr>";
echo "<tr><td>Valid HTTPS</td><td>" . (($parsedUrl['scheme'] ?? '') === 'https' ? 'Yes ✅' : 'No ❌') . "</td></tr>";
echo "</table>";

echo "<h2>3. Basic Connectivity Test</h2>";
echo "<p>Testing if the URL responds to basic requests...</p>";

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Test</th><th>Result</th></tr>";
echo "<tr><td>HTTP Status</td><td>" . $httpCode . " " . ($httpCode == 200 ? '✅' : ($httpCode >= 400 ? '❌' : '⚠️')) . "</td></tr>";
echo "<tr><td>cURL Error</td><td>" . ($error ? $error . ' ❌' : 'None ✅') . "</td></tr>";
echo "<tr><td>Response Length</td><td>" . strlen($response) . " characters</td></tr>";
echo "</table>";

if ($response) {
    echo "<h4>Response Preview:</h4>";
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow-y: auto;'>";
    echo htmlspecialchars(substr($response, 0, 500));
    if (strlen($response) > 500) {
        echo "\n... (truncated)";
    }
    echo "</pre>";
}

echo "<h2>4. JSON POST Test</h2>";
echo "<p>Testing with minimal JSON data...</p>";

$testData = array(
    'Name' => 'Test User',
    'ID' => '12345678'
);

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => GOOGLE_SHEETS_TIMEOUT,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'User-Agent: BUCUC-Diagnostic/1.0'
    )
));

$postResponse = curl_exec($ch);
$postHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$postError = curl_error($ch);
curl_close($ch);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Test</th><th>Result</th></tr>";
echo "<tr><td>POST HTTP Status</td><td>" . $postHttpCode . " " . ($postHttpCode == 200 ? '✅' : ($postHttpCode >= 400 ? '❌' : '⚠️')) . "</td></tr>";
echo "<tr><td>POST cURL Error</td><td>" . ($postError ? $postError . ' ❌' : 'None ✅') . "</td></tr>";
echo "<tr><td>POST Response Length</td><td>" . strlen($postResponse) . " characters</td></tr>";
echo "</table>";

if ($postResponse) {
    echo "<h4>POST Response:</h4>";
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow-y: auto;'>";
    echo htmlspecialchars($postResponse);
    echo "</pre>";
    
    // Try to decode JSON
    $jsonResponse = json_decode($postResponse, true);
    if ($jsonResponse) {
        echo "<h4>Parsed JSON Response:</h4>";
        echo "<pre style='background: #e8f5e8; padding: 10px;'>";
        print_r($jsonResponse);
        echo "</pre>";
    }
}

echo "<h2>5. Google Apps Script Deployment Checklist</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;'>";
echo "<h4>⚠️ If you're getting 'Unauthorized' errors, check these:</h4>";
echo "<ol>";
echo "<li><strong>Deploy as Web App:</strong> In Google Apps Script, click 'Deploy' → 'New Deployment'</li>";
echo "<li><strong>Type:</strong> Select 'Web app' as the type</li>";
echo "<li><strong>Execute as:</strong> Select 'Me' or the account that owns the spreadsheet</li>";
echo "<li><strong>Who has access:</strong> Select 'Anyone' (for external requests to work)</li>";
echo "<li><strong>Version:</strong> Use 'New' and provide a description</li>";
echo "<li><strong>Copy URL:</strong> Copy the Web app URL and update your config file</li>";
echo "</ol>";
echo "</div>";

echo "<div style='background: #d1ecf1; padding: 15px; border-left: 4px solid #bee5eb; margin: 10px 0;'>";
echo "<h4>💡 Sample Google Apps Script Code</h4>";
echo "<p>Make sure your Google Apps Script contains the correct code to handle JSON POST requests. Check the README_GoogleSheets.md file for the complete code.</p>";
echo "</div>";

echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #c3e6cb; margin: 10px 0;'>";
echo "<h4>✅ Next Steps</h4>";
echo "<ol>";
echo "<li>Update your Google Apps Script with the JSON handling code from README_GoogleSheets.md</li>";
echo "<li>Deploy it as a Web App with 'Anyone' access</li>";
echo "<li>Update the URL in config/google_sheets_config.php if needed</li>";
echo "<li>Run this diagnostic script again to verify</li>";
echo "<li>Test with test_google_sheets.php</li>";
echo "</ol>";
echo "</div>";

echo "<p><strong>Generated at:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
