<?php
/**
 * Check Hostinger PHP Configuration
 * Run this on your Hostinger website to see what's available
 */

echo "<h2>Hostinger PHP Configuration Check</h2>";
echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px;'>";

// Basic PHP info
echo "<h3>1. PHP Version</h3>";
echo "PHP Version: " . phpversion() . "<br>";

// Check PDO
echo "<h3>2. PDO Extensions</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO extension is loaded<br>";
    
    $drivers = PDO::getAvailableDrivers();
    echo "Available PDO drivers: " . implode(', ', $drivers) . "<br>";
    
    if (in_array('mysql', $drivers)) {
        echo "✅ PDO MySQL driver is available<br>";
    } else {
        echo "❌ PDO MySQL driver is NOT available<br>";
        echo "<strong>This is the problem! You need to enable PDO MySQL in your Hostinger PHP configuration.</strong><br>";
    }
} else {
    echo "❌ PDO extension is NOT loaded<br>";
}

// Check other required extensions
echo "<h3>3. Other Required Extensions</h3>";
$required_extensions = ['curl', 'json', 'mbstring', 'openssl'];

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension is loaded<br>";
    } else {
        echo "❌ $ext extension is NOT loaded<br>";
    }
}

// Check if we can connect to database
echo "<h3>4. Database Connection Test</h3>";
try {
    require_once 'Database/config.php';
    echo "✅ Database config loaded<br>";
    echo "DB Host: " . DB_HOST . "<br>";
    echo "DB Name: " . DB_NAME . "<br>";
    echo "DB User: " . DB_USER . "<br>";
    
    if (extension_loaded('pdo') && in_array('mysql', PDO::getAvailableDrivers())) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        echo "✅ Database connection successful!<br>";
    } else {
        echo "❌ Cannot test database connection - PDO MySQL not available<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Check email configuration
echo "<h3>5. Email Configuration</h3>";
try {
    require_once 'config/email_config.php';
    echo "✅ Email config loaded<br>";
    echo "SMTP Host: " . SMTP_HOST . "<br>";
    echo "SMTP Username: " . SMTP_USERNAME . "<br>";
} catch (Exception $e) {
    echo "❌ Email config error: " . $e->getMessage() . "<br>";
}

echo "<h3>6. Recommendations</h3>";
if (!extension_loaded('pdo') || !in_array('mysql', PDO::getAvailableDrivers())) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>❌ Action Required</h4>";
    echo "<p><strong>You need to enable PDO MySQL in your Hostinger PHP configuration:</strong></p>";
    echo "<ol>";
    echo "<li>Log into your Hostinger control panel</li>";
    echo "<li>Go to 'Advanced' → 'PHP Configuration'</li>";
    echo "<li>Enable 'PDO MySQL' extension</li>";
    echo "<li>Save the configuration</li>";
    echo "<li>Test your accept button again</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>✅ Configuration Looks Good</h4>";
    echo "<p>Your PHP configuration appears to be correct. The accept button should work now.</p>";
    echo "</div>";
}

echo "</div>";
?>
