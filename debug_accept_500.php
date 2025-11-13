<?php
/**
 * Debug script to identify the 500 error when accepting applications
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "<h2>Debugging Accept Button 500 Error</h2>";
echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px;'>";

// Test 1: Basic PHP
echo "<h3>1. Basic PHP Test</h3>";
echo "✅ PHP is working<br>";

// Test 2: Session
echo "<h3>2. Session Test</h3>";
session_start();
if (isset($_SESSION["admin"])) {
    echo "✅ Admin session exists<br>";
} else {
    echo "❌ No admin session - this might be the issue!<br>";
    echo "Please log in as admin first.<br>";
}

// Test 3: Database connection
echo "<h3>3. Database Connection Test</h3>";
try {
    if (file_exists('Database/db.php')) {
        require_once 'Database/db.php';
        $database = new Database();
        $pdo = $database->createConnection();
        echo "✅ Database connection successful<br>";
        
        // Test a simple query
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM members");
        $result = $stmt->fetch();
        echo "✅ Database query successful. Total members: " . $result['count'] . "<br>";
    } else {
        echo "❌ Database file missing<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 4: Email configuration
echo "<h3>4. Email Configuration Test</h3>";
try {
    if (file_exists('config/email_config.php')) {
        require_once 'config/email_config.php';
        echo "✅ Email config loaded<br>";
        
        if (defined('SMTP_HOST') && defined('SMTP_USERNAME') && defined('SMTP_PASSWORD')) {
            echo "✅ Email constants defined<br>";
        } else {
            echo "❌ Email constants missing<br>";
        }
    } else {
        echo "❌ Email config file missing<br>";
    }
} catch (Exception $e) {
    echo "❌ Email config error: " . $e->getMessage() . "<br>";
}

// Test 5: PHPMailer
echo "<h3>5. PHPMailer Test</h3>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "✅ Vendor autoload loaded<br>";
        
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            echo "✅ PHPMailer class exists<br>";
            
            // Try to create an instance
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            echo "✅ PHPMailer instance created<br>";
        } else {
            echo "❌ PHPMailer class missing<br>";
        }
    } else {
        echo "❌ Vendor autoload missing<br>";
    }
} catch (Exception $e) {
    echo "❌ PHPMailer error: " . $e->getMessage() . "<br>";
}

// Test 6: Google Sheets integration
echo "<h3>6. Google Sheets Integration Test</h3>";
if (file_exists('Action/google_sheets_integration.php')) {
    echo "✅ Google Sheets file exists<br>";
    try {
        require_once 'Action/google_sheets_integration.php';
        echo "✅ Google Sheets file loaded<br>";
    } catch (Exception $e) {
        echo "❌ Google Sheets error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "⚠️ Google Sheets file missing (optional)<br>";
}

// Test 7: Simulate the accept action
echo "<h3>7. Simulate Accept Action</h3>";
if (isset($_SESSION["admin"])) {
    try {
        // Get a test member
        $stmt = $pdo->prepare("SELECT * FROM members WHERE membership_status = 'New_member' LIMIT 1");
        $stmt->execute();
        $member = $stmt->fetch();
        
        if ($member) {
            echo "✅ Found test member: " . $member['full_name'] . "<br>";
            echo "✅ Member ID: " . $member['id'] . "<br>";
            echo "✅ G-Suite Email: " . $member['gsuite_email'] . "<br>";
        } else {
            echo "❌ No pending members found<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error finding test member: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Cannot test accept action - no admin session<br>";
}

echo "<h3>Summary</h3>";
echo "<p>If any test shows ❌, that's likely the cause of your 500 error.</p>";
echo "<p>Most common causes:</p>";
echo "<ul>";
echo "<li>No admin session (need to log in first)</li>";
echo "<li>Database connection issues</li>";
echo "<li>Missing email configuration</li>";
echo "<li>PHPMailer not properly installed</li>";
echo "</ul>";

echo "</div>";
?>
