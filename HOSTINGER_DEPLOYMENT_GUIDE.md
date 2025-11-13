# BUCuC Website - Hostinger Deployment Guide

## Email Configuration Changes Required

When deploying your BUCuC website to Hostinger, you need to make the following changes to ensure PHPMailer works correctly.

### 1. Create Hostinger Email Account

1. **Log into Hostinger hPanel**
2. **Navigate to**: Emails → Email Accounts
3. **Create new email account**:
   - Email: `hr@yourdomain.com` (replace with your actual domain)
   - Password: Create a strong password
   - Note down these credentials

### 2. Update Email Configuration

**File to modify**: `config/email_config.php`

**Current configuration** (already updated):

```php
<?php

// Hostinger SMTP Configuration
// Replace 'yourdomain.com' with your actual domain name
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_SECURITY', 'tls'); // Use 'ssl' for port 465
define('SMTP_USERNAME', 'hr@yourdomain.com'); // Replace with your Hostinger email
define('SMTP_PASSWORD', 'your_hostinger_email_password'); // Replace with your Hostinger email password
define('FROM_EMAIL', 'hr@yourdomain.com'); // Replace with your Hostinger email
define('FROM_NAME', 'BRAC University Cultural Club');

// Alternative SSL Configuration (uncomment if TLS doesn't work)
// define('SMTP_PORT', 465);
// define('SMTP_SECURITY', 'ssl');
```

**Required changes**:

1. Replace `yourdomain.com` with your actual domain name
2. Replace `your_hostinger_email_password` with your actual email password

**Example** (if your domain is `bucuc.org`):

```php
define('SMTP_USERNAME', 'hr@bucuc.org');
define('SMTP_PASSWORD', 'your_actual_password_here');
define('FROM_EMAIL', 'hr@bucuc.org');
```

### 3. Test Email Functionality

**After deployment, run the test script**:

1. Upload `test_hostinger_email.php` to your website root
2. Visit: `https://yourdomain.com/test_hostinger_email.php`
3. Follow the on-screen instructions
4. **Delete the test file after successful testing**

### 4. Hostinger SMTP Settings Reference

| Setting        | Value                       |
| -------------- | --------------------------- |
| SMTP Host      | `smtp.hostinger.com`        |
| SMTP Port      | `587` (TLS) or `465` (SSL)  |
| Encryption     | TLS or SSL                  |
| Authentication | Required                    |
| Username       | Your full email address     |
| Password       | Your email account password |

### 5. Troubleshooting Common Issues

#### Issue: "Connection refused" or "Connection timeout"

**Solutions**:

- Try switching from TLS (port 587) to SSL (port 465)
- Uncomment the SSL configuration lines in `email_config.php`
- Contact Hostinger support to verify SMTP is enabled

#### Issue: "Authentication failed"

**Solutions**:

- Double-check email credentials
- Ensure email account is properly created in hPanel
- Try logging into the email account via webmail first

#### Issue: "Email not delivered"

**Solutions**:

- Check spam/junk folders
- Verify recipient email addresses are valid
- Monitor Hostinger email sending limits

### 6. Security Considerations

1. **Never commit real passwords to version control**
2. **Use environment variables** (recommended for production):

   ```php
   define('SMTP_PASSWORD', $_ENV['HOSTINGER_EMAIL_PASSWORD']);
   ```

3. **Set proper file permissions**:
   ```bash
   chmod 600 config/email_config.php
   ```

### 7. Email Sending Limits

Hostinger has email sending limits:

- **Shared Hosting**: ~200 emails per hour
- **Business Hosting**: ~500 emails per hour
- **VPS/Cloud**: Higher limits

**Recommendations**:

- Monitor your email sending volume
- Consider upgrading hosting plan if needed
- Implement email queuing for high-volume sending

### 8. Files Modified for Hostinger

1. ✅ `config/email_config.php` - Updated SMTP settings
2. ✅ `test_hostinger_email.php` - Test script (delete after testing)
3. ✅ `HOSTINGER_DEPLOYMENT_GUIDE.md` - This guide

### 9. Post-Deployment Checklist

- [ ] Create Hostinger email account
- [ ] Update `config/email_config.php` with real credentials
- [ ] Upload all files to Hostinger
- [ ] Run email test script
- [ ] Test application acceptance process
- [ ] Delete test files
- [ ] Monitor email delivery
- [ ] Set up email monitoring/logging

### 10. Support Resources

- **Hostinger Support**: https://support.hostinger.com
- **PHPMailer Documentation**: https://github.com/PHPMailer/PHPMailer
- **Email Configuration Help**: Contact Hostinger support for SMTP issues

---

**Important**: Always test email functionality after deployment and before going live with your application system.
