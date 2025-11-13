<?php
echo "🔧 BRAC University Cultural Club - Complete Setup\n";
echo "==============================================\n\n";

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'bucuc';

try {
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to MySQL successfully!\n\n";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$dbname' created/verified!\n\n";
    
    // Use the database
    $pdo->exec("USE `$dbname`");
    
    // Create adminpanel table
    $adminTableSql = "CREATE TABLE IF NOT EXISTS `adminpanel` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('main_admin', 'admin') DEFAULT 'admin',
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($adminTableSql);
    echo "✅ Admin panel table created!\n\n";
    
    // Create members table
    $membersTableSql = "CREATE TABLE IF NOT EXISTS `members` (
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
    )";
    
    $pdo->exec($membersTableSql);
    echo "✅ Members table created!\n\n";
    
    // Check if admin exists
    $checkAdmin = $pdo->query("SELECT * FROM adminpanel WHERE email = 'admin@bucuc.com'");
    $adminExists = $checkAdmin->fetch();
    
    if ($adminExists) {
        // Update existing admin password
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $updateSql = "UPDATE adminpanel SET password = :password WHERE email = 'admin@bucuc.com'";
        $stmt = $pdo->prepare($updateSql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        echo "✅ Admin password updated successfully!\n\n";
    } else {
        // Create new admin
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insertSql = "INSERT INTO adminpanel (username, email, password, role) VALUES ('RUDIAN AHMED', 'admin@bucuc.com', :password, 'main_admin')";
        $stmt = $pdo->prepare($insertSql);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        echo "✅ Admin account created successfully!\n\n";
    }
    
    // Insert sample member
    $checkMember = $pdo->query("SELECT * FROM members WHERE email = 'john.doe@bracu.ac.bd'");
    $memberExists = $checkMember->fetch();
    
    if (!$memberExists) {
        $memberPassword = password_hash('member123', PASSWORD_DEFAULT);
        $memberSql = "INSERT INTO members (
            full_name, university_id, email, password, department, phone, 
            semester, gender, date_of_birth, membership_status, motivation
        ) VALUES (
            'John Doe', '2021-01-01-001', 'john.doe@bracu.ac.bd', :password,
            'Computer Science and Engineering', '+8801234567890', '6th', 'Male',
            '2000-01-01', 'Current Member', 'I want to join the Cultural Club to develop my creative skills.'
        )";
        
        $stmt = $pdo->prepare($memberSql);
        $stmt->bindParam(':password', $memberPassword);
        $stmt->execute();
        echo "✅ Sample member created successfully!\n\n";
    } else {
        echo "✅ Sample member already exists!\n\n";
    }
    
    // Show current data
    echo "📊 Current Database Status:\n";
    echo "========================\n";
    
    $adminCount = $pdo->query("SELECT COUNT(*) FROM adminpanel")->fetchColumn();
    $memberCount = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
    
    echo "Admins: $adminCount\n";
    echo "Members: $memberCount\n\n";
    
    echo "🎉 SETUP COMPLETE!\n";
    echo "==================\n\n";
    
    echo "🔑 LOGIN CREDENTIALS:\n";
    echo "=====================\n";
    echo "Admin Login:\n";
    echo "- URL: http://localhost/dashboard/bucuc/admin-login.php\n";
    echo "- Email: admin@bucuc.com\n";
    echo "- Password: admin123\n\n";
    
    echo "Member Login:\n";
    echo "- URL: http://localhost/dashboard/bucuc/login.php\n";
    echo "- Email: john.doe@bracu.ac.bd\n";
    echo "- Password: member123\n\n";
    
    echo "✅ You can now login successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n\n";
    echo "🔧 Troubleshooting:\n";
    echo "1. Make sure XAMPP is running\n";
    echo "2. Make sure MySQL service is started\n";
    echo "3. Check your database credentials\n";
    echo "4. Try restarting XAMPP\n";
}
?> 