<?php
echo "🔧 Updating Database for Simple Gender Distribution (Male/Female Only)\n";
echo "==================================================================\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL successfully!\n\n";
    
    // Use database
    $pdo->exec('USE bucuc');
    echo "✅ Using database 'bucuc'\n\n";
    
    // Update gender_tracking column to only allow Male/Female
    $pdo->exec('ALTER TABLE members MODIFY COLUMN gender_tracking ENUM("Male", "Female") NOT NULL DEFAULT "Male"');
    echo "✅ Updated gender_tracking to Male/Female only\n";
    
    // Update gender column to only allow Male/Female
    $pdo->exec('ALTER TABLE members MODIFY COLUMN gender ENUM("Male", "Female") NOT NULL');
    echo "✅ Updated gender to Male/Female only\n\n";
    
    // Remove any "Other" entries and convert to Male (or you can choose Female)
    $pdo->exec('UPDATE members SET gender_tracking = "Male", gender = "Male" WHERE gender_tracking = "Other" OR gender = "Other"');
    echo "✅ Converted any 'Other' entries to 'Male'\n\n";
    
    // Show current data
    $stmt = $pdo->query("SELECT event_category, gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY event_category, gender_tracking ORDER BY event_category, gender_tracking");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Current Gender Distribution (Male/Female Only):\n";
    echo "================================================\n";
    foreach ($results as $row) {
        echo "{$row['event_category']} - {$row['gender_tracking']}: {$row['count']}\n";
    }
    
    echo "\n🎉 Gender Distribution Simplified!\n";
    echo "================================\n";
    echo "✅ Only Male/Female tracking\n";
    echo "✅ Simplified chart controls\n";
    echo "✅ Cleaner data structure\n\n";
    
    echo "🔗 Test the feature:\n";
    echo "- Admin Dashboard: http://localhost/dashboard/bucuc/admin-login.php\n";
    echo "- Sign up new members: http://localhost/dashboard/bucuc/index.php#footer\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 