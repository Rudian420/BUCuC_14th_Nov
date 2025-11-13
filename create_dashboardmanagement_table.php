<?php
require_once 'Database/db.php';

// Create database connection
$database = new Database();
$conn = $database->createConnection();

try {
    // SQL to create dashboardmanagement table
    $sql = "CREATE TABLE IF NOT EXISTS dashboardmanagement (
        id INT AUTO_INCREMENT PRIMARY KEY,
        totalmembers INT NOT NULL DEFAULT 0,
        pending_applications INT NOT NULL DEFAULT 0,
        completedevents INT NOT NULL DEFAULT 0,
        others INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_by INT,
        updated_by INT,
        INDEX idx_created_at (created_at)
    )";
    
    $conn->exec($sql);
    echo "Table 'dashboardmanagement' created successfully!<br>";
    
    // Check if table is empty and insert initial record if needed
    $checkSql = "SELECT COUNT(*) as count FROM dashboardmanagement";
    $stmt = $conn->prepare($checkSql);
    $stmt->execute();
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        echo "Table is empty, inserting initial record...<br>";
        
        $insertSql = "INSERT INTO dashboardmanagement (totalmembers, pending_applications, completedevents, others) 
                      VALUES (0, 0, 0, 0)";
        $conn->exec($insertSql);
        echo "Initial record inserted successfully!<br>";
    } else {
        echo "Table already contains " . $result['count'] . " record(s).<br>";
    }
    
    // Display table structure
    $showSql = "DESCRIBE dashboardmanagement";
    $stmt = $conn->prepare($showSql);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
