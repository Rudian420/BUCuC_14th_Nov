<?php
require_once 'Database/db.php';

$database = new Database();
$conn = $database->createConnection();

try {
    // Check if members table exists and get its structure
    $describeTable = "DESCRIBE members";
    $stmt = $conn->prepare($describeTable);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Members Table Structure:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    $hasGenderTracking = false;
    $hasEventCategory = false;
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'gender_tracking') {
            $hasGenderTracking = true;
        }
        if ($column['Field'] === 'event_category') {
            $hasEventCategory = true;
        }
    }
    echo "</table>";
    
    // Add missing columns
    if (!$hasGenderTracking) {
        echo "<p style='color: orange;'>⚠️ Adding gender_tracking column...</p>";
        $conn->exec("ALTER TABLE members ADD COLUMN gender_tracking ENUM('Male', 'Female', 'Other') NULL");
        echo "<p style='color: green;'>✅ Added gender_tracking column</p>";
        
        // Update existing records
        $conn->exec("UPDATE members SET gender_tracking = gender WHERE gender_tracking IS NULL");
        echo "<p style='color: green;'>✅ Updated existing records with gender_tracking data</p>";
    }
    
    if (!$hasEventCategory) {
        echo "<p style='color: orange;'>⚠️ Adding event_category column...</p>";
        $conn->exec("ALTER TABLE members ADD COLUMN event_category VARCHAR(100) NULL");
        echo "<p style='color: green;'>✅ Added event_category column</p>";
        
        // Update existing records with random categories
        $categories = ['Music', 'Dance', 'Drama', 'Art', 'Poetry'];
        foreach ($categories as $i => $category) {
            $conn->exec("UPDATE members SET event_category = '$category' WHERE event_category IS NULL AND id % " . count($categories) . " = $i");
        }
        echo "<p style='color: green;'>✅ Updated existing records with event categories</p>";
    }
    
    // Check current data
    echo "<h3>Current Members Data:</h3>";
    $dataSql = "SELECT id, full_name, gender, gender_tracking, event_category FROM members LIMIT 10";
    $dataStmt = $conn->prepare($dataSql);
    $dataStmt->execute();
    $members = $dataStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($members)) {
        echo "<p style='color: red;'>❌ No members found in database!</p>";
        echo "<p><a href='debug_gender_chart.php' style='color: blue; font-weight: bold;'>→ Click here to create dummy members</a></p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Gender</th><th>Gender Tracking</th><th>Event Category</th></tr>";
        
        foreach ($members as $member) {
            echo "<tr>";
            echo "<td>" . $member['id'] . "</td>";
            echo "<td>" . htmlspecialchars($member['full_name']) . "</td>";
            echo "<td>" . $member['gender'] . "</td>";
            echo "<td>" . $member['gender_tracking'] . "</td>";
            echo "<td>" . $member['event_category'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show gender distribution
        echo "<h3>Gender Distribution Summary:</h3>";
        $genderSql = "SELECT gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY gender_tracking";
        $genderStmt = $conn->prepare($genderSql);
        $genderStmt->execute();
        $genderData = $genderStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>";
        foreach ($genderData as $row) {
            echo "<li><strong>" . $row['gender_tracking'] . ":</strong> " . $row['count'] . " members</li>";
        }
        echo "</ul>";
        
        echo "<p><a href='admin_dashboard.php' style='color: green; font-weight: bold;'>→ Go to Admin Dashboard to see the chart</a></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
