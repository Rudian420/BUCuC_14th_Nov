# BUCuC Website - Personal Email Setup Guide

## Using Your Personal Email Instead of Hostinger Email

Yes, you can absolutely use your personal email (Gmail, Outlook, Yahoo, etc.) to send application emails! This is often easier and more reliable than setting up a new email account.

## 🎯 Benefits of Using Personal Email

- ✅ **No additional setup required** - Use your existing email
- ✅ **Better deliverability** - Personal emails are less likely to be marked as spam
- ✅ **Familiar interface** - Manage emails from your usual email client
- ✅ **No hosting restrictions** - Works regardless of your hosting provider
- ✅ **More reliable** - Major email providers have better uptime

## 📧 Supported Email Providers

### 1. **Gmail** (Recommended)

- **SMTP Host**: `smtp.gmail.com`
- **Port**: `587` (TLS) or `465` (SSL)
- **Security**: TLS or SSL
- **Requirement**: **App Password** (not your regular password)

### 2. **Outlook/Hotmail**

- **SMTP Host**: `smtp-mail.outlook.com`
- **Port**: `587` (TLS)
- **Security**: TLS
- **Requirement**: App Password or enable "Less secure app access"

### 3. **Yahoo**

- **SMTP Host**: `smtp.mail.yahoo.com`
- **Port**: `587` (TLS) or `465` (SSL)
- **Security**: TLS or SSL
- **Requirement**: **App Password**

## 🔧 Setup Instructions

### Step 1: Choose Your Email Provider

Edit `config/email_config.php` and uncomment the configuration for your preferred email provider.

**For Gmail (Recommended):**

```php
// ===== GMAIL CONFIGURATION =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURITY', 'tls');
define('SMTP_USERNAME', 'your.email@gmail.com'); // Replace with your Gmail
define('SMTP_PASSWORD', 'your_app_password'); // Replace with your Gmail App Password
define('FROM_EMAIL', 'your.email@gmail.com'); // Replace with your Gmail
define('FROM_NAME', 'BRAC University Cultural Club');
```

**For Outlook:**

```php
// ===== OUTLOOK/HOTMAIL CONFIGURATION =====
define('SMTP_HOST', 'smtp-mail.outlook.com');
define('SMTP_PORT', 587);
define('SMTP_SECURITY', 'tls');
define('SMTP_USERNAME', 'your.email@outlook.com'); // Replace with your Outlook
define('SMTP_PASSWORD', 'your_password'); // Replace with your password
define('FROM_EMAIL', 'your.email@outlook.com'); // Replace with your Outlook
define('FROM_NAME', 'BRAC University Cultural Club';
```

### Step 2: Get App Password (Gmail & Yahoo)

**For Gmail:**

1. Go to [Google Account Settings](https://myaccount.google.com/)
2. Navigate to **Security** → **2-Step Verification**
3. Scroll down to **App passwords**
4. Generate a new app password for "Mail"
5. Use this 16-character password in your config

**For Yahoo:**

1. Go to [Yahoo Account Security](https://login.yahoo.com/account/security)
2. Enable **2-Step Verification** if not already enabled
3. Generate an **App-specific password**
4. Use this password in your config

**For Outlook:**

- Use your regular password (less secure but simpler)
- Or enable "Less secure app access" in account settings

### Step 3: Update Configuration

1. **Replace placeholder values** in `config/email_config.php`:

   - `your.email@gmail.com` → Your actual email address
   - `your_app_password` → Your actual app password

2. **Comment out unused configurations** to avoid confusion

### Step 4: Test Email Functionality

1. Upload `test_hostinger_email.php` to your website
2. Visit: `https://yourdomain.com/test_hostinger_email.php`
3. Follow the test results
4. **Delete the test file after successful testing**

## 🚨 Important Security Notes

### Gmail App Passwords

- **Never use your regular Gmail password**
- App passwords are 16 characters long
- They can be revoked individually if compromised
- More secure than regular passwords

### Password Management

- Store credentials securely
- Don't commit real passwords to version control
- Consider using environment variables in production

## 🔍 Troubleshooting Common Issues

### Issue: "Authentication failed"

**Solutions:**

- For Gmail: Ensure you're using App Password, not regular password
- For Outlook: Check if "Less secure app access" is enabled
- Verify email address and password are correct

### Issue: "Connection timeout"

**Solutions:**

- Check if your hosting provider blocks SMTP connections
- Try different ports (587 for TLS, 465 for SSL)
- Contact your hosting provider about SMTP restrictions

### Issue: "Email not delivered"

**Solutions:**

- Check spam/junk folders
- Verify recipient email addresses
- Monitor your email provider's sending limits

## 📊 Email Provider Comparison

| Provider      | Setup Difficulty | Security   | Reliability | Daily Limits   |
| ------------- | ---------------- | ---------- | ----------- | -------------- |
| **Gmail**     | ⭐⭐⭐⭐⭐       | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐  | 500 emails/day |
| **Outlook**   | ⭐⭐⭐⭐         | ⭐⭐⭐⭐   | ⭐⭐⭐⭐⭐  | 300 emails/day |
| **Yahoo**     | ⭐⭐⭐⭐         | ⭐⭐⭐⭐   | ⭐⭐⭐⭐    | 500 emails/day |
| **Hostinger** | ⭐⭐⭐           | ⭐⭐⭐     | ⭐⭐⭐      | 200-500/hour   |

## 🎯 Recommendation

**Use Gmail with App Password** - It's the most reliable, secure, and easiest to set up option.

## 📝 Example Configuration Files

### Gmail Example

```php
<?php
// Gmail Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURITY', 'tls');
define('SMTP_USERNAME', 'hr.bucuc@gmail.com');
define('SMTP_PASSWORD', 'abcd efgh ijkl mnop'); // 16-character app password
define('FROM_EMAIL', 'hr.bucuc@gmail.com');
define('FROM_NAME', 'BRAC University Cultural Club');
?>
```

### Outlook Example

```php
<?php
// Outlook Configuration
define('SMTP_HOST', 'smtp-mail.outlook.com');
define('SMTP_PORT', 587);
define('SMTP_SECURITY', 'tls');
define('SMTP_USERNAME', 'hr.bucuc@outlook.com');
define('SMTP_PASSWORD', 'your_regular_password');
define('FROM_EMAIL', 'hr.bucuc@outlook.com');
define('FROM_NAME', 'BRAC University Cultural Club';
```

## ✅ Post-Setup Checklist

- [ ] Choose your email provider
- [ ] Get app password (if using Gmail/Yahoo)
- [ ] Update `config/email_config.php`
- [ ] Test email functionality
- [ ] Verify email delivery
- [ ] Delete test files
- [ ] Monitor email sending limits

---

**Need Help?** Check your email provider's official documentation for SMTP settings and app password generation.
