<?php
/**
 * Debug script for accept button issues
 * This will help identify what's causing the error
 */

session_start();
if (!isset($_SESSION["admin"])) {
    die("Please log in as admin first");
}

echo "<h2>Debug Accept Button Issues</h2>";
echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px;'>";

// Test 1: Check if required files exist
echo "<h3>1. Checking Required Files</h3>";
$requiredFiles = [
    'Database/db.php',
    'config/email_config.php',
    'vendor/autoload.php',
    'Action/google_sheets_integration.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

// Test 2: Check if constants are defined
echo "<h3>2. Checking Email Configuration</h3>";
try {
    require_once 'config/email_config.php';
    echo "✅ Email config loaded<br>";
    echo "SMTP_HOST: " . (defined('SMTP_HOST') ? SMTP_HOST : 'NOT DEFINED') . "<br>";
    echo "SMTP_USERNAME: " . (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NOT DEFINED') . "<br>";
    echo "FROM_EMAIL: " . (defined('FROM_EMAIL') ? FROM_EMAIL : 'NOT DEFINED') . "<br>";
} catch (Exception $e) {
    echo "❌ Email config error: " . $e->getMessage() . "<br>";
}

// Test 3: Check PHPMailer
echo "<h3>3. Checking PHPMailer</h3>";
try {
    require_once 'vendor/autoload.php';
    echo "✅ Vendor autoload loaded<br>";
    
    // Check if PHPMailer classes exist
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "✅ PHPMailer class exists<br>";
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ PHPMailer instance created successfully<br>";
    } else {
        echo "❌ PHPMailer class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ PHPMailer error: " . $e->getMessage() . "<br>";
}

// Test 4: Check database connection
echo "<h3>4. Checking Database Connection</h3>";
try {
    require_once 'Database/db.php';
    $database = new Database();
    $pdo = $database->createConnection();
    echo "✅ Database connection successful<br>";
    
    // Check if members table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'members'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Members table exists<br>";
        
        // Check for pending members
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM members WHERE membership_status = 'New_member'");
        $result = $stmt->fetch();
        echo "Pending members: " . $result['count'] . "<br>";
    } else {
        echo "❌ Members table does not exist<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 5: Check Google Sheets integration
echo "<h3>5. Checking Google Sheets Integration</h3>";
if (file_exists('Action/google_sheets_integration.php')) {
    try {
        require_once 'Action/google_sheets_integration.php';
        echo "✅ Google Sheets integration loaded<br>";
        
        if (function_exists('sendToGoogleSheets')) {
            echo "✅ sendToGoogleSheets function exists<br>";
        } else {
            echo "❌ sendToGoogleSheets function missing<br>";
        }
    } catch (Exception $e) {
        echo "❌ Google Sheets integration error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "⚠️ Google Sheets integration file missing (this is optional)<br>";
}

// Test 6: Test email sending (dry run)
echo "<h3>6. Testing Email Configuration (Dry Run)</h3>";
try {
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURITY === 'tls' ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;
        
        echo "✅ Email configuration appears valid<br>";
        echo "SMTP Host: " . SMTP_HOST . "<br>";
        echo "SMTP Port: " . SMTP_PORT . "<br>";
        echo "SMTP Security: " . SMTP_SECURITY . "<br>";
    } else {
        echo "❌ PHPMailer class not available for testing<br>";
    }
} catch (Exception $e) {
    echo "❌ Email configuration error: " . $e->getMessage() . "<br>";
}

echo "<h3>7. Test Accept Button</h3>";
echo "<p>If all the above checks pass, try clicking the accept button again.</p>";
echo "<p>If you still get an error, please copy the exact error message and share it.</p>";

echo "</div>";
?>
