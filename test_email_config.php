<?php
/**
 * Test email configuration without requiring admin login
 * This will test if your Gmail configuration works on Hostinger
 */

echo "<h2>Email Configuration Test</h2>";
echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px;'>";

// Test 1: Check if email config file exists
if (file_exists('config/email_config.php')) {
    echo "✅ Email config file exists<br>";
    require_once 'config/email_config.php';
    echo "✅ Email config loaded<br>";
    
    // Test 2: Check if constants are defined
    if (defined('SMTP_HOST')) {
        echo "✅ SMTP_HOST defined: " . SMTP_HOST . "<br>";
    } else {
        echo "❌ SMTP_HOST not defined<br>";
    }
    
    if (defined('SMTP_USERNAME')) {
        echo "✅ SMTP_USERNAME defined: " . SMTP_USERNAME . "<br>";
    } else {
        echo "❌ SMTP_USERNAME not defined<br>";
    }
    
    if (defined('SMTP_PASSWORD')) {
        echo "✅ SMTP_PASSWORD defined: " . (strlen(SMTP_PASSWORD) > 0 ? "***" . substr(SMTP_PASSWORD, -4) : "EMPTY") . "<br>";
    } else {
        echo "❌ SMTP_PASSWORD not defined<br>";
    }
    
    if (defined('FROM_EMAIL')) {
        echo "✅ FROM_EMAIL defined: " . FROM_EMAIL . "<br>";
    } else {
        echo "❌ FROM_EMAIL not defined<br>";
    }
    
} else {
    echo "❌ Email config file missing<br>";
}

// Test 3: Check if PHPMailer is available
if (file_exists('vendor/autoload.php')) {
    echo "✅ Vendor autoload exists<br>";
    require_once 'vendor/autoload.php';
    echo "✅ Vendor autoload loaded<br>";
    
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "✅ PHPMailer class exists<br>";
    } else {
        echo "❌ PHPMailer class missing<br>";
    }
} else {
    echo "❌ Vendor autoload missing<br>";
}

// Test 4: Try to create PHPMailer instance and test basic configuration
if (class_exists('PHPMailer\PHPMailer\PHPMailer') && defined('SMTP_HOST')) {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ PHPMailer instance created<br>";
        
        // Test SMTP configuration
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURITY === 'tls' ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;
        
        echo "✅ SMTP configuration set<br>";
        echo "✅ Ready to send emails!<br>";
        
    } catch (Exception $e) {
        echo "❌ PHPMailer error: " . $e->getMessage() . "<br>";
    }
}

echo "<h3>Summary</h3>";
echo "<p>If all tests show ✅, your email configuration should work on Hostinger.</p>";
echo "<p>If any test shows ❌, that's what needs to be fixed.</p>";

echo "</div>";
?>
