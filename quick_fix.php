<?php
echo "🔧 Quick Fix for Admin Login\n";
echo "===========================\n\n";

// Try different connection methods
$connections = [
    ['host' => 'localhost', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => 3306],
    ['host' => 'localhost', 'port' => 3306, 'socket' => '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock']
];

$pdo = null;
$connected = false;

foreach ($connections as $config) {
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']}";
        if (isset($config['socket'])) {
            $dsn .= ";unix_socket={$config['socket']}";
        }
        
        $pdo = new PDO($dsn, 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ Connected using: {$config['host']}:{$config['port']}\n";
        $connected = true;
        break;
        
    } catch (PDOException $e) {
        echo "❌ Failed: {$config['host']}:{$config['port']} - " . $e->getMessage() . "\n";
    }
}

if (!$connected) {
    echo "\n❌ Could not connect to MySQL\n";
    echo "Please check:\n";
    echo "1. XAMPP Control Panel is open\n";
    echo "2. MySQL service is started (green)\n";
    echo "3. Apache service is started (green)\n";
    exit();
}

try {
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `bucuc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'bucuc' ready!\n";
    
    // Use database
    $pdo->exec("USE `bucuc`");
    
    // Create admin table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `adminpanel` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('main_admin', 'admin') DEFAULT 'admin',
        `status` ENUM('active', 'inactive') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✅ Admin table created!\n";
    
    // Update admin password
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM adminpanel WHERE email = 'admin@bucuc.com'");
    $stmt->execute();
    
    if ($stmt->fetch()) {
        // Update existing admin
        $updateStmt = $pdo->prepare("UPDATE adminpanel SET password = ?, role = 'main_admin' WHERE email = 'admin@bucuc.com'");
        $updateStmt->execute([$hashedPassword]);
        echo "✅ Admin password updated!\n";
    } else {
        // Create new admin
        $insertStmt = $pdo->prepare("INSERT INTO adminpanel (username, email, password, role) VALUES (?, ?, ?, ?)");
        $insertStmt->execute(['RUDIAN AHMED', 'admin@bucuc.com', $hashedPassword, 'main_admin']);
        echo "✅ Admin account created!\n";
    }
    
    echo "\n🎉 SUCCESS! Admin login is now fixed!\n";
    echo "====================================\n";
    echo "🔑 Login Credentials:\n";
    echo "- Email: admin@bucuc.com\n";
    echo "- Password: admin123\n";
    echo "- URL: http://localhost/dashboard/bucuc/admin-login.php\n\n";
    
    echo "✅ You can now login successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 