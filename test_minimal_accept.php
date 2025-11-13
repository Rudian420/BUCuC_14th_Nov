<?php
/**
 * Minimal test to find the exact cause of 500 error
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check admin session
if (!isset($_SESSION["admin"])) {
    die("Error: No admin session. Please log in first.");
}

echo "<h2>Minimal Accept Test</h2>";
echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px;'>";

try {
    echo "<h3>Step 1: Basic Setup</h3>";
    echo "✅ PHP working<br>";
    echo "✅ Admin session exists<br>";
    
    echo "<h3>Step 2: Loading Files</h3>";
    require_once 'Database/db.php';
    echo "✅ Database file loaded<br>";
    
    require_once 'config/email_config.php';
    echo "✅ Email config loaded<br>";
    
    require_once 'vendor/autoload.php';
    echo "✅ PHPMailer loaded<br>";
    
    echo "<h3>Step 3: Database Connection</h3>";
    $database = new Database();
    $pdo = $database->createConnection();
    echo "✅ Database connected<br>";
    
    echo "<h3>Step 4: Find Test Member</h3>";
    $stmt = $pdo->prepare("SELECT * FROM members WHERE membership_status = 'New_member' LIMIT 1");
    $stmt->execute();
    $member = $stmt->fetch();
    
    if (!$member) {
        echo "❌ No pending members found<br>";
        echo "<p>Please add a test member first by visiting the signup page.</p>";
        exit;
    }
    
    echo "✅ Test member found: " . htmlspecialchars($member['full_name']) . "<br>";
    echo "G-Suite Email: " . htmlspecialchars($member['gsuite_email']) . "<br>";
    
    echo "<h3>Step 5: Test Email Sending</h3>";
    
    // Create PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    echo "✅ PHPMailer instance created<br>";
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURITY;
    $mail->Port = SMTP_PORT;
    echo "✅ SMTP settings configured<br>";
    
    // Recipients
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress($member['gsuite_email'], $member['full_name']);
    echo "✅ Recipients set<br>";
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from BUCuC System';
    $mail->Body = '<h2>Test Email</h2><p>This is a test email to verify the system is working.</p>';
    $mail->AltBody = 'This is a test email to verify the system is working.';
    echo "✅ Email content set<br>";
    
    // Send email
    echo "<h4>Attempting to send email...</h4>";
    $result = $mail->send();
    
    if ($result) {
        echo "✅ Email sent successfully!<br>";
        echo "<p style='color: green;'><strong>SUCCESS:</strong> The email system is working correctly!</p>";
    } else {
        echo "❌ Email failed to send<br>";
        echo "<p style='color: red;'><strong>ERROR:</strong> This is likely the cause of your 500 error.</p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Error Found:</h3>";
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>This is likely the cause of your 500 error.</p>";
    
    // Show more details about the error
    echo "<h4>Error Details:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</div>";
?>
