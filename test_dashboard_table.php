<?php
require_once 'Database/db.php';

// Create database connection
$database = new Database();
$conn = $database->createConnection();

echo "<h2>Dashboard Management Table Test</h2>";

try {
    // First, create the table if it doesn't exist
    $create_table_sql = "CREATE TABLE IF NOT EXISTS dashboardmanagement (
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
    
    $conn->exec($create_table_sql);
    echo "<p style='color: green;'>✓ Table 'dashboardmanagement' exists or was created successfully!</p>";
    
    // Show table structure
    echo "<h3>Table Structure:</h3>";
    $describe_sql = "DESCRIBE dashboardmanagement";
    $stmt = $conn->prepare($describe_sql);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr style='background-color: #f0f0f0;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($column['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Show current data in the table
    echo "<h3>Current Data:</h3>";
    $select_sql = "SELECT * FROM dashboardmanagement ORDER BY created_at DESC";
    $stmt = $conn->prepare($select_sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($data) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th>";
        echo "<th>Total Members</th>";
        echo "<th>Pending Applications</th>";
        echo "<th>Completed Events</th>";
        echo "<th>Others</th>";
        echo "<th>Created At</th>";
        echo "<th>Updated At</th>";
        echo "<th>Created By</th>";
        echo "<th>Updated By</th>";
        echo "</tr>";
        
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['totalmembers']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pending_applications']) . "</td>";
            echo "<td>" . htmlspecialchars($row['completedevents']) . "</td>";
            echo "<td>" . htmlspecialchars($row['others']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_by'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['updated_by'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total records: " . count($data) . "</p>";
    } else {
        echo "<p><em>No data found in the table.</em></p>";
        echo "<p>The table is ready to receive data from your form!</p>";
    }
    
    // Show form link
    echo "<h3>Test the Form:</h3>";
    echo "<p>Go to your admin dashboard and use the 'Add New Record' button to test the form.</p>";
    echo "<p><a href='admin_dashboard.php' target='_blank'>Open Admin Dashboard</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Show form handler file status
echo "<h3>Form Handler Status:</h3>";
if (file_exists('Action/form_handler.php')) {
    echo "<p style='color: green;'>✓ Form handler exists: Action/form_handler.php</p>";
    echo "<p>Form action points to: Action/form_handler.php</p>";
} else {
    echo "<p style='color: red;'>❌ Form handler missing: Action/form_handler.php</p>";
}

// Show database configuration
echo "<h3>Database Configuration:</h3>";
if (file_exists('Database/config.php') && file_exists('Database/db.php')) {
    echo "<p style='color: green;'>✓ Database configuration files exist</p>";
    echo "<p>Database Name: " . DB_NAME . "</p>";
    echo "<p>Database Host: " . DB_HOST . "</p>";
    echo "<p>Database User: " . DB_USER . "</p>";
} else {
    echo "<p style='color: red;'>❌ Database configuration files missing</p>";
}
?>
