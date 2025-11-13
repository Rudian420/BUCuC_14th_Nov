<?php
require_once 'Database/db.php';

try {
    $database = new Database();
    $conn = $database->createConnection();
    
    echo "Checking Members Table Structure:\n";
    echo "================================\n\n";
    
    // Check if table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'members'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Members table does not exist.\n";
        exit;
    }
    
    echo "✅ Members table exists.\n\n";
    
    // Show table structure
    echo "Table Structure:\n";
    echo "----------------\n";
    $stmt = $conn->query("DESCRIBE members");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-20s %-20s %-10s %-10s %-10s %s\n", 
            $row['Field'], 
            $row['Type'], 
            $row['Null'], 
            $row['Key'], 
            $row['Default'], 
            $row['Extra']
        );
    }
    
    echo "\nSample Data Count:\n";
    echo "------------------\n";
    $stmt = $conn->query("SELECT COUNT(*) as total FROM members");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total members: " . $result['total'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
