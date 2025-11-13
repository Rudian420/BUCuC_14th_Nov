<?php
echo "🔧 Updating Database for Three Gender Options (Male/Female/Other)\n";
echo "=============================================================\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL successfully!\n\n";
    
    // Use database
    $pdo->exec('USE bucuc');
    echo "✅ Using database 'bucuc'\n\n";
    
    // Update gender_tracking column to allow Male/Female/Other
    $pdo->exec('ALTER TABLE members MODIFY COLUMN gender_tracking ENUM("Male", "Female", "Other") NOT NULL DEFAULT "Male"');
    echo "✅ Updated gender_tracking to Male/Female/Other\n";
    
    // Update gender column to allow Male/Female/Other
    $pdo->exec('ALTER TABLE members MODIFY COLUMN gender ENUM("Male", "Female", "Other") NOT NULL');
    echo "✅ Updated gender to Male/Female/Other\n\n";
    
    // Add some sample "Other" entries for testing
    $pdo->exec('UPDATE members SET gender_tracking = "Other", gender = "Other" WHERE email = "john.doe@bracu.ac.bd"');
    echo "✅ Added sample 'Other' entry for testing\n\n";
    
    // Show current data
    $stmt = $pdo->query("SELECT gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY gender_tracking ORDER BY count DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Current Total Gender Distribution:\n";
    echo "===================================\n";
    foreach ($results as $row) {
        echo "{$row['gender_tracking']}: {$row['count']}\n";
    }
    
    echo "\n🎉 Three Gender Options Ready!\n";
    echo "=============================\n";
    echo "✅ Male/Female/Other tracking\n";
    echo "✅ Total gender count chart\n";
    echo "✅ Blue/Pink/Purple colors\n\n";
    
    echo "🔗 Test the feature:\n";
    echo "- Admin Dashboard: http://localhost/dashboard/bucuc/admin-login.php\n";
    echo "- Sign up new members: http://localhost/dashboard/bucuc/index.php#footer\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 