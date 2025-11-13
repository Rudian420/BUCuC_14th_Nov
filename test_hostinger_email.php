<?php
/**
 * Test script for PHPMailer with Personal Email
 * Run this script to verify email functionality after deployment
 */

require_once 'config/email_config.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Test email configuration
function testEmailConfiguration() {
    echo "<h2>Testing PHPMailer Configuration with Personal Email</h2>";
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px;'>";
    
    // Display current configuration
    echo "<h3>Current Configuration:</h3>";
    echo "<ul>";
    echo "<li><strong>SMTP Host:</strong> " . SMTP_HOST . "</li>";
    echo "<li><strong>SMTP Port:</strong> " . SMTP_PORT . "</li>";
    echo "<li><strong>SMTP Security:</strong> " . SMTP_SECURITY . "</li>";
    echo "<li><strong>Username:</strong> " . SMTP_USERNAME . "</li>";
    echo "<li><strong>From Email:</strong> " . FROM_EMAIL . "</li>";
    echo "<li><strong>From Name:</strong> " . FROM_NAME . "</li>";
    echo "</ul>";
    
    // Check if credentials are configured
    if (strpos(SMTP_USERNAME, 'hr.bucuc@gmail.com') !== false && 
        strpos(SMTP_PASSWORD, 'khqhirokxbkojzih') !== false) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>✅ Configuration Ready</h4>";
        echo "<p>Your Gmail credentials are properly configured for sending emails from your BUCuC website.</p>";
        echo "</div>";
        return true;
    } else {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>⚠️ Configuration Check</h4>";
        echo "<p>Please verify your Gmail credentials in <code>config/email_config.php</code>.</p>";
        echo "</div>";
        return false;
    }
    
    return true;
}

// Test email sending
function sendTestEmail($toEmail = null) {
    if (!$toEmail) {
        $toEmail = SMTP_USERNAME; // Send to the same email account
    }
    
    $mail = new PHPMailer(true);
    
    try {
        echo "<h3>Testing Email Sending...</h3>";
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURITY === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = SMTP_PORT;
        
        // Enable verbose debug output (remove in production)
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        
        // Recipients
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($toEmail, 'Test Recipient');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test Email from BUCuC - Personal Email Setup';
        $mail->Body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🎉 Email Test Successful!</h1>
                </div>
                <div class="content">
                    <div class="success">
                        <h3>✅ PHPMailer is working correctly with your personal email!</h3>
                        <p>This test email confirms that your BUCuC application system can successfully send emails to applicants.</p>
                    </div>
                    
                    <h3>Test Details:</h3>
                    <ul>
                        <li><strong>Sent from:</strong> ' . FROM_EMAIL . '</li>
                        <li><strong>SMTP Server:</strong> ' . SMTP_HOST . ':' . SMTP_PORT . '</li>
                        <li><strong>Security:</strong> ' . strtoupper(SMTP_SECURITY) . '</li>
                        <li><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</li>
                    </ul>
                    
                    <p>Your application acceptance emails will now be delivered successfully to new club members!</p>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li>Remove this test file from your production server</li>
                        <li>Test the actual application acceptance process</li>
                        <li>Monitor email delivery in your personal email account</li>
                    </ul>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->AltBody = "Test Email from BUCuC - Personal Email Setup\n\nThis test email confirms that PHPMailer is working correctly with your personal email. Your application acceptance emails will now be delivered successfully to new club members.\n\nTimestamp: " . date('Y-m-d H:i:s');
        
        $mail->send();
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>✅ Success!</h4>";
        echo "<p>Test email sent successfully to: <strong>$toEmail</strong></p>";
        echo "<p>Check your email inbox to confirm delivery.</p>";
        echo "</div>";
        
        return true;
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>❌ Error!</h4>";
        echo "<p><strong>Message could not be sent.</strong></p>";
        echo "<p><strong>Mailer Error:</strong> {$mail->ErrorInfo}</p>";
        echo "</div>";
        
        // Common troubleshooting tips
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4>🔧 Troubleshooting Tips:</h4>";
        echo "<ul>";
        echo "<li>Verify your personal email credentials in <code>config/email_config.php</code></li>";
        echo "<li>Ensure your email account has SMTP access enabled</li>";
        echo "<li>For Gmail: Use App Password instead of regular password</li>";
        echo "<li>For Outlook: Enable 'Less secure app access' or use App Password</li>";
        echo "<li>Check if your email provider allows SMTP connections</li>";
        echo "</ul>";
        echo "</div>";
        
        return false;
    }
}

// Main execution
echo "<!DOCTYPE html><html><head><title>BUCuC Email Test - Personal Email</title></head><body>";
echo "<div style='background: #e9ecef; padding: 20px; min-height: 100vh;'>";

if (testEmailConfiguration()) {
    // Only attempt to send email if configuration is valid
    sendTestEmail();
} else {
    echo "<p>Please update your email configuration before testing.</p>";
}

echo "</div></body></html>";
?>
