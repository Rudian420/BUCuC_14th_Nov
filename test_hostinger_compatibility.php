<?php
echo "<h2>Hostinger Compatibility Test</h2>";

// Check PHP version
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Required: 8.1+<br>";
echo "Status: " . (version_compare(PHP_VERSION, '8.1.0', '>=') ? "✅ OK" : "❌ FAIL") . "<br><br>";

// Check extensions
$required = ['dom', 'fileinfo', 'gd', 'iconv', 'libxml', 'mbstring', 'simplexml', 'xml', 'xmlreader', 'xmlwriter', 'zip', 'zlib'];
echo "<h3>Required Extensions:</h3>";
$all_ok = true;
foreach ($required as $ext) {
    $status = extension_loaded($ext) ? "✅ OK" : "❌ MISSING";
    if (!extension_loaded($ext)) $all_ok = false;
    echo "$ext: $status<br>";
}

// Check if vendor directory exists
echo "<br><h3>Dependencies:</h3>";
if (file_exists('vendor/autoload.php')) {
    echo "Composer autoload: ✅ OK<br>";
} else {
    echo "Composer autoload: ❌ MISSING<br>";
    $all_ok = false;
}

if (file_exists('vendor/phpoffice/phpspreadsheet')) {
    echo "PHPSpreadsheet: ✅ OK<br>";
} else {
    echo "PHPSpreadsheet: ❌ MISSING<br>";
    $all_ok = false;
}

// Check database connection
echo "<br><h3>Database Connection:</h3>";
try {
    require_once 'Database/db.php';
    $database = new Database();
    $pdo = $database->createConnection();
    echo "Database: ✅ OK<br>";

    // Check if members table exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM members");
    $result = $stmt->fetch();
    $memberCount = $result['count'];
    echo "Members table: ✅ OK ({$memberCount} records)<br>";
} catch (Exception $e) {
    echo "Database: ❌ ERROR - " . $e->getMessage() . "<br>";
    $all_ok = false;
}

echo "<br><h3>Overall Status: " . ($all_ok ? "✅ READY FOR EXCEL EXPORT" : "❌ MISSING REQUIREMENTS") . "</h3>";

if ($all_ok) {
    echo "<p><a href='Action/export_members_excel_hostinger.php' style='background: green; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Test Excel Export</a></p>";
    echo "<p><a href='pending_applications.php' style='background: blue; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Go to Pending Applications</a></p>";
} else {
    echo "<p style='color: red;'>Please fix the missing requirements before using Excel export.</p>";
}

echo "<hr>";
echo "<p><strong>Memory Usage:</strong> " . round(memory_get_usage() / 1024 / 1024, 2) . " MB</p>";
echo "<p><strong>Max Memory:</strong> " . ini_get('memory_limit') . "</p>";
echo "<p><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds</p>";
