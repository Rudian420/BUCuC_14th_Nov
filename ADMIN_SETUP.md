# Admin Account Management System - Setup Guide

## Overview
This system provides comprehensive admin account management capabilities for the BRAC University Cultural Club website. Only Main Admins can access and manage admin accounts.

## Features

### ✅ Main Admin Capabilities
- **Create New Admin**: Add new admin accounts with name, Gmail, and password
- **Edit Admin Password**: Reset/change any admin's password securely
- **Delete Admin**: Remove existing admin accounts (except self)
- **Password Security**: All passwords are securely hashed and never visible

### 🔐 Security Features
- **Role-based Access**: Only Main Admins can access admin management
- **Password Hashing**: Uses PHP's `password_hash()` with `PASSWORD_DEFAULT`
- **Input Validation**: Comprehensive validation for all inputs
- **SQL Injection Protection**: Prepared statements for all database queries
- **Self-Protection**: Admins cannot delete their own accounts

## Database Setup

### 1. Update Database Schema
Run the updated SQL in `Database/db.sql`:

```sql
-- Update the adminpanel table structure
CREATE TABLE adminpanel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('main_admin', 'admin') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert main admin with hashed password
INSERT INTO adminpanel (username, email, password, role) VALUES 
("RUDIAN AHMED", "admin@bucuc.com", "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", "main_admin");
```

### 2. Update Existing Admin Password
Run the password update script once:

```bash
php update_admin_password.php
```

This will update the existing admin password to use proper hashing.

## File Structure

```
├── admin_dashboard.php          # Main dashboard with admin management UI
├── admin-login.php              # Updated login with password verification
├── Action/
│   └── admin_management.php     # Backend API for admin operations
├── Database/
│   ├── db.php                   # Database connection class
│   ├── config.php               # Database configuration
│   └── db.sql                   # Updated database schema
├── AdminCss/
│   └── Dashboard.css            # Updated styles for admin management
└── update_admin_password.php    # One-time password update script
```

## Usage Instructions

### 1. Login as Main Admin
- Email: `admin@bucuc.com`
- Password: `admin123`

### 2. Access Admin Management
1. Login to the admin dashboard
2. Navigate to "Admin Management" in the sidebar (only visible to Main Admins)
3. The admin list will automatically load

### 3. Create New Admin
1. Click "Add New Admin" button
2. Fill in the required fields:
   - **Admin Name**: Full name of the admin
   - **Gmail Address**: Valid Gmail address
   - **Password**: Minimum 6 characters
   - **Role**: Choose between "Admin" or "Main Admin"
3. Click "Create Admin"

### 4. Edit Admin Password
1. Click the key icon (🔑) next to any admin in the list
2. Enter the new password and confirm it
3. Click "Update Password"

### 5. Delete Admin
1. Click the trash icon (🗑️) next to any admin in the list
2. Confirm the deletion in the modal
3. Click "Delete Admin"

## Security Notes

### Password Security
- **Never Visible**: Passwords are never displayed or stored in plain text
- **Secure Hashing**: Uses PHP's `password_hash()` with `PASSWORD_DEFAULT`
- **Minimum Length**: Passwords must be at least 6 characters
- **Validation**: All inputs are validated and sanitized

### Access Control
- **Role-based**: Only Main Admins can access admin management
- **Session-based**: Uses PHP sessions for authentication
- **Self-protection**: Admins cannot delete their own accounts

### Database Security
- **Prepared Statements**: All queries use prepared statements
- **Input Sanitization**: All inputs are properly sanitized
- **Error Handling**: Comprehensive error handling without exposing sensitive data

## API Endpoints

### GET / POST `Action/admin_management.php`

#### Parameters:
- `action`: Operation to perform
  - `get_admins`: Retrieve all admin accounts
  - `create_admin`: Create new admin account
  - `update_password`: Update admin password
  - `delete_admin`: Delete admin account

#### Required Session:
- User must be logged in as Main Admin
- Session variables: `$_SESSION['admin']`, `$_SESSION['admin_role']`

#### Response Format:
```json
{
    "success": true/false,
    "message": "Operation result message",
    "data": [] // For get_admins action
}
```

## Troubleshooting

### Common Issues

1. **"Access denied" error**
   - Ensure you're logged in as a Main Admin
   - Check session variables are set correctly

2. **"Database error"**
   - Verify database connection in `Database/config.php`
   - Check if the `adminpanel` table exists and has correct structure

3. **"Email already exists"**
   - Use a different email address for new admin accounts
   - Email addresses must be unique

4. **"Password too short"**
   - Ensure password is at least 6 characters long

### Debug Mode
To enable debug mode, add this to the top of `Action/admin_management.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Maintenance

### Regular Tasks
1. **Monitor Admin Accounts**: Regularly review the admin list
2. **Update Passwords**: Encourage regular password updates
3. **Audit Logs**: Monitor for suspicious activities

### Backup
- Regularly backup the `adminpanel` table
- Keep secure copies of admin credentials

## Support
For technical support, contact: `bucuc@support.ac.bd`

---

**Note**: This system is designed for the BRAC University Cultural Club and should only be used by authorized personnel. All admin operations are logged and monitored for security purposes. 