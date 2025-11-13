# Password Hash Verification Login System - Setup Guide

## 🎯 **Overview**
This system provides secure password hash verification for both admin and member login systems. All passwords are securely hashed using PHP's `password_hash()` function and verified using `password_verify()`.

## 🔐 **Security Features**

### ✅ **Password Security**
- **Secure Hashing**: Uses `password_hash()` with `PASSWORD_DEFAULT`
- **Never Visible**: Passwords are never stored or displayed in plain text
- **Minimum Length**: Passwords must be at least 6 characters
- **Input Validation**: Comprehensive validation for all inputs

### ✅ **Login Systems**
1. **Admin Login**: `admin-login.php` - For administrators
2. **Member Login**: `login.php` - For club members
3. **Secure Logout**: `logout.php` - Handles both admin and member logout

## 📁 **Files Created/Updated**

### **New Files:**
- `login.php` - Member login page with password verification
- `member_dashboard.php` - Member dashboard after login
- `Action/signup_handler.php` - Secure member registration with password hashing
- `setup_members_database.sql` - Database structure for members table

### **Updated Files:**
- `admin-login.php` - Enhanced with password verification
- `index.php` - Updated signup form to use secure registration
- `logout.php` - Enhanced to handle both admin and member logout

## 🗄️ **Database Setup**

### **Step 1: Create Members Table**
Run the SQL file to create the members table:

```sql
-- Import setup_members_database.sql in phpMyAdmin
-- Or run the SQL commands manually:

USE `bucuc`;

CREATE TABLE IF NOT EXISTS `members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `university_id` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `gsuite_email` VARCHAR(100) NULL,
    `password` VARCHAR(255) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `facebook_url` VARCHAR(255) NULL,
    `membership_status` ENUM('New Member', 'Current Member', 'Previous Member') NOT NULL,
    `motivation` TEXT NOT NULL,
    `status` ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **Step 2: Update Admin Password**
Run the admin password update script:

```bash
php fix_admin_login.php
```

## 🔑 **Login Credentials**

### **Admin Login:**
- **URL**: `http://localhost/dashboard/bucuc/admin-login.php`
- **Email**: `admin@bucuc.com`
- **Password**: `admin123`
- **Role**: Main Admin

### **Member Login:**
- **URL**: `http://localhost/dashboard/bucuc/login.php`
- **Email**: `john.doe@bracu.ac.bd` (sample member)
- **Password**: `member123`
- **Role**: Current Member

## 🚀 **How to Use**

### **1. Admin Login**
1. Go to `admin-login.php`
2. Enter admin credentials
3. Access admin dashboard with admin management features

### **2. Member Registration**
1. Go to main site (`index.php`)
2. Scroll to "Sign Up" section
3. Fill in all required fields
4. Password will be automatically hashed
5. Redirected to login page after successful registration

### **3. Member Login**
1. Go to `login.php`
2. Enter registered email and password
3. Access member dashboard with personal information

### **4. Secure Logout**
- Click logout button from any dashboard
- Session is completely destroyed
- Redirected to main page

## 🔧 **Technical Implementation**

### **Password Hashing Process:**

```php
// When creating new user (signup)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// When verifying login
if (password_verify($inputPassword, $storedHash)) {
    // Login successful
}
```

### **Session Management:**

```php
// Admin session variables
$_SESSION['admin'] = true;
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_role'] = $admin['role'];

// Member session variables
$_SESSION['logged_in'] = true;
$_SESSION['member_id'] = $member['id'];
$_SESSION['member_name'] = $member['full_name'];
```

### **Security Features:**

1. **Input Validation**: All inputs are validated and sanitized
2. **SQL Injection Protection**: Prepared statements for all queries
3. **Session Security**: Proper session management and cleanup
4. **Password Requirements**: Minimum 6 characters, secure hashing
5. **Email Validation**: Proper email format validation

## 📱 **User Interface**

### **Login Pages:**
- **Modern Design**: Glassmorphism effects with backdrop blur
- **Responsive**: Works on all devices
- **User-Friendly**: Clear error messages and success notifications
- **Consistent**: Matches your existing website design

### **Dashboards:**
- **Admin Dashboard**: Full admin management capabilities
- **Member Dashboard**: Personal information and quick actions
- **Navigation**: Easy access to all features

## 🛠️ **Troubleshooting**

### **Common Issues:**

1. **"Database connection failed"**
   - Ensure XAMPP is running
   - Check MySQL service is started
   - Verify database credentials

2. **"Invalid email or password"**
   - Check if user exists in database
   - Verify password was hashed during registration
   - Ensure email format is correct

3. **"Email already registered"**
   - Use a different email address
   - Check if user already exists

4. **"Password too short"**
   - Ensure password is at least 6 characters
   - Check password validation

### **Debug Mode:**
Add this to any PHP file for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🔒 **Security Best Practices**

### **Password Security:**
- Never store passwords in plain text
- Use strong password requirements
- Implement password reset functionality
- Regular security audits

### **Session Security:**
- Proper session timeout
- Secure session storage
- CSRF protection
- Input sanitization

### **Database Security:**
- Prepared statements only
- Proper error handling
- Regular backups
- Access control

## 📞 **Support**

For technical support:
- **Email**: `bucuc@support.ac.bd`
- **Documentation**: Check this guide and code comments
- **Database**: Use phpMyAdmin for database management

---

**Note**: This system is designed for the BRAC University Cultural Club and implements industry-standard security practices. All passwords are securely hashed and never stored in plain text. 