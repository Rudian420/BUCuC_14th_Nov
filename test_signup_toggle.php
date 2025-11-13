<?php
// Test script to verify signup toggle functionality
require_once 'Database/db.php';

try {
    $database = new Database();
    $conn = $database->createConnection();
    
    echo "<h3>Testing Signup Status Toggle</h3>";
    
    // Check if signup_status table exists
    $tableQuery = "SHOW TABLES LIKE 'signup_status'";
    $stmt = $conn->prepare($tableQuery);
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;
    
    echo "<p><strong>Table 'signup_status' exists:</strong> " . ($tableExists ? 'Yes' : 'No') . "</p>";
    
    if ($tableExists) {
        // Show table structure
        $structureQuery = "DESCRIBE signup_status";
        $stmt = $conn->prepare($structureQuery);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h4>Table Structure:</h4>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show current data
        $dataQuery = "SELECT * FROM signup_status";
        $stmt = $conn->prepare($dataQuery);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h4>Current Data:</h4>";
        if (empty($data)) {
            echo "<p>No data found in table.</p>";
        } else {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Is Enabled</th><th>Updated At</th><th>Updated By</th></tr>";
            
            foreach ($data as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['is_enabled']) . " (" . ($row['is_enabled'] ? 'TRUE' : 'FALSE') . ")</td>";
                echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                echo "<td>" . htmlspecialchars($row['updated_by'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'>Table does not exist. It should be created automatically when the signup_status_handler.php is first called.</p>";
    }
    
    // Test the creation manually
    echo "<h4>Creating Table and Initial Record:</h4>";
    
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS signup_status (
            id INT PRIMARY KEY DEFAULT 1,
            is_enabled TINYINT(1) NOT NULL DEFAULT 1,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            updated_by VARCHAR(100) DEFAULT NULL
        )
    ";
    
    try {
        $conn->exec($createTableQuery);
        echo "<p style='color: green;'>✓ Table created successfully (or already exists)</p>";
        
        // Insert default record if table is empty
        $checkQuery = "SELECT COUNT(*) FROM signup_status";
        $stmt = $conn->prepare($checkQuery);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            $insertQuery = "INSERT INTO signup_status (id, is_enabled, updated_by) VALUES (1, 1, 'system')";
            $stmt = $conn->prepare($insertQuery);
            $stmt->execute();
            echo "<p style='color: green;'>✓ Default record inserted</p>";
        } else {
            echo "<p style='color: blue;'>ℹ Default record already exists</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error creating table: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Database connection error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { margin: 10px 0; }
    th, td { text-align: left; }
    th { background-color: #f0f0f0; }
</style>
