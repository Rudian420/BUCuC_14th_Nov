<?php
echo "🔧 Creating Members Table with Gender Distribution\n";
echo "===============================================\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL successfully!\n\n";
    
    // Use database
    $pdo->exec('USE bucuc');
    echo "✅ Using database 'bucuc'\n\n";
    
    // Create members table (without password field)
    $createTableSql = "CREATE TABLE IF NOT EXISTS `members` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(100) NOT NULL,
        `university_id` VARCHAR(50) NOT NULL UNIQUE,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `gsuite_email` VARCHAR(100) NULL,
        `department` VARCHAR(100) NOT NULL,
        `phone` VARCHAR(20) NOT NULL,
        `semester` VARCHAR(20) NOT NULL,
        `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
        `date_of_birth` DATE NOT NULL,
        `facebook_url` VARCHAR(255) NULL,
        `membership_status` ENUM('New Member', 'Current Member', 'Previous Member') NOT NULL DEFAULT 'New Member',
        `event_category` VARCHAR(50) NOT NULL DEFAULT 'Music',
        `gender_tracking` ENUM('Male', 'Female', 'Other') NOT NULL DEFAULT 'Male',
        `motivation` TEXT NOT NULL,
        `status` ENUM('active', 'inactive', 'pending') DEFAULT 'active',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($createTableSql);
    echo "✅ Members table created successfully!\n\n";
    
    // Insert sample data (without password)
    $sampleData = [
        ['John Doe', '2021-01-01-001', 'john.doe@bracu.ac.bd', 'Computer Science and Engineering', '+8801234567890', '6th', 'Male', '2000-01-01', 'Current Member', 'Music', 'Male', 'I want to join the Cultural Club to develop my creative skills.'],
        ['Sarah Ahmed', '2021-01-01-002', 'sarah.ahmed@bracu.ac.bd', 'Computer Science and Engineering', '+8801234567891', '4th', 'Female', '2001-05-15', 'Current Member', 'Performance', 'Female', 'I love dancing and want to showcase my talent.'],
        ['Rahim Khan', '2021-01-01-003', 'rahim.khan@bracu.ac.bd', 'Electrical and Electronic Engineering', '+8801234567892', '6th', 'Male', '2000-08-20', 'Current Member', 'Performance', 'Male', 'I want to explore my acting skills.'],
        ['Fatima Begum', '2021-01-01-004', 'fatima.begum@bracu.ac.bd', 'English and Humanities', '+8801234567893', '3rd', 'Female', '2002-03-10', 'Current Member', 'Creative', 'Female', 'I am passionate about painting and drawing.'],
        ['Imran Hossain', '2021-01-01-005', 'imran.hossain@bracu.ac.bd', 'Mathematics and Natural Sciences', '+8801234567894', '5th', 'Male', '2000-12-25', 'Current Member', 'PR', 'Male', 'I write poetry and want to share my work.'],
        ['Aisha Rahman', '2021-01-01-006', 'aisha.rahman@bracu.ac.bd', 'Business Administration', '+8801234567895', '2nd', 'Female', '2003-07-08', 'Current Member', 'Music', 'Female', 'I play guitar and want to perform.']
    ];
    
    foreach ($sampleData as $data) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO members (
            full_name, university_id, email, department, phone, 
            semester, gender, date_of_birth, membership_status, event_category, gender_tracking, motivation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data[0], $data[1], $data[2], $data[3], $data[4],
            $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]
        ]);
    }
    
    echo "✅ Added sample data for testing\n\n";
    
    // Show current data
    $stmt = $pdo->query("SELECT event_category, gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY event_category, gender_tracking ORDER BY event_category, gender_tracking");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Current Gender Distribution:\n";
    echo "==============================\n";
    foreach ($results as $row) {
        echo "{$row['event_category']} - {$row['gender_tracking']}: {$row['count']}\n";
    }
    
    echo "\n🎉 Gender Distribution Feature is now ready!\n";
    echo "==========================================\n";
    echo "✅ Members table created\n";
    echo "✅ Sample data added\n";
    echo "✅ API endpoint created\n";
    echo "✅ Dashboard chart added\n\n";
    
    echo "🔗 Test the feature:\n";
    echo "- Admin Dashboard: http://localhost/dashboard/bucuc/admin-login.php\n";
    echo "- Sign up new members: http://localhost/dashboard/bucuc/index.php#footer\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 