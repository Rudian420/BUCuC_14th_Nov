# BUCuC Website - Hostinger + Gmail Setup

## ✅ **Your Setup is Ready!**

You're using **Hostinger for web hosting** + **Your existing Gmail for sending emails**.

## 🔧 **Current Configuration**

Your `config/email_config.php` is already configured with:

- **SMTP Host**: `smtp.gmail.com`
- **Port**: `587` (TLS)
- **Username**: `hr.bucuc@gmail.com` ✅
- **Password**: `khqhirokxbkojzih` ✅ (App Password)
- **From Email**: `hr.bucuc@gmail.com` ✅

## 🚀 **Deployment Steps**

### 1. **Upload to Hostinger**

- Upload all your website files to Hostinger
- Ensure `vendor/` folder is included (for PHPMailer)

### 2. **Test Email Functionality**

- Upload `test_hostinger_email.php` to your website root
- Visit: `https://yourdomain.com/test_hostinger_email.php`
- The test should show "✅ Configuration Ready"

### 3. **Verify Email Sending**

- Run the test to send a test email to yourself
- Check your Gmail inbox for the test email

### 4. **Clean Up**

- Delete `test_hostinger_email.php` after successful testing
- Your application system is now ready!

## 🎯 **What This Means**

- **Hostinger**: Hosts your website files and database
- **Gmail**: Sends all application acceptance emails
- **PHPMailer**: Handles the email sending process
- **No changes needed**: Your existing Gmail credentials work perfectly

## 🔍 **Why This Setup Works**

1. **Gmail SMTP** works from any hosting provider
2. **Your App Password** (`khqhirokxbkojzih`) is already configured
3. **PHPMailer** handles the email delivery
4. **No hosting restrictions** on Gmail SMTP

## 📧 **Email Flow**

1. **User applies** → Application stored in Hostinger database
2. **Admin accepts** → PHPMailer sends email via Gmail SMTP
3. **Email delivered** → Applicant receives acceptance email from `hr.bucuc@gmail.com`

## 🚨 **Important Notes**

- ✅ **Gmail App Password** is already configured
- ✅ **No additional email setup** required on Hostinger
- ✅ **Works immediately** after file upload
- ✅ **Same email address** (`hr.bucuc@gmail.com`) for all communications

## 🧪 **Testing**

Run the test script to verify everything works:

```bash
https://yourdomain.com/test_hostinger_email.php
```

## 🎉 **You're All Set!**

Your BUCuC website will work perfectly on Hostinger while sending emails through your existing Gmail account. No additional configuration needed!
