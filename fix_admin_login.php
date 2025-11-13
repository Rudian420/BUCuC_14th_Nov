<?php
// Simple script to fix admin login
// This will update the admin password to work with the new system

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'bucuc';
    $user = 'root';
    $pass = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected successfully!\n\n";
    
    // Check if adminpanel table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'adminpanel'");
    if ($stmt->rowCount() == 0) {
        echo "❌ adminpanel table does not exist. Creating it...\n";
        
        // Create the table
        $sql = "CREATE TABLE adminpanel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('main_admin', 'admin') DEFAULT 'admin',
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "✅ adminpanel table created successfully!\n";
    } else {
        echo "✅ adminpanel table exists.\n";
    }
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT * FROM adminpanel WHERE email = ?");
    $stmt->execute(['admin@bucuc.com']);
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "✅ Admin account found.\n";
        echo "Current username: " . $admin['username'] . "\n";
        echo "Current email: " . $admin['email'] . "\n";
        echo "Current role: " . $admin['role'] . "\n";
        
        // Update password to hashed version
        $newPassword = "admin123";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $pdo->prepare("UPDATE adminpanel SET password = ?, role = 'main_admin' WHERE email = ?");
        $updateStmt->execute([$hashedPassword, 'admin@bucuc.com']);
        
        echo "✅ Admin password updated successfully!\n";
        echo "✅ Admin role set to 'main_admin'\n";
        
    } else {
        echo "❌ Admin account not found. Creating new admin account...\n";
        
        // Create new admin account
        $newPassword = "admin123";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $insertStmt = $pdo->prepare("INSERT INTO adminpanel (username, email, password, role) VALUES (?, ?, ?, ?)");
        $insertStmt->execute(['RUDIAN AHMED', 'admin@bucuc.com', $hashedPassword, 'main_admin']);
        
        echo "✅ New admin account created successfully!\n";
    }
    
    echo "\n🎉 ADMIN LOGIN CREDENTIALS:\n";
    echo "Email: admin@bucuc.com\n";
    echo "Password: admin123\n";
    echo "Role: Main Admin\n";
    echo "\nYou can now login to the admin dashboard!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\n🔧 Troubleshooting:\n";
    echo "1. Make sure XAMPP is running\n";
    echo "2. Make sure MySQL service is started\n";
    echo "3. Make sure the 'bucuc' database exists\n";
    echo "4. Check your database credentials in Database/config.php\n";
}
?> 