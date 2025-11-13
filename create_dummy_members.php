<?php
echo "🎭 Creating 12 Dummy Members for Gender Distribution Testing\n";
echo "=========================================================\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL successfully!\n\n";
    
    // Use database
    $pdo->exec('USE bucuc');
    echo "✅ Using database 'bucuc'\n\n";
    
    // Clear existing data to start fresh
    $pdo->exec('DELETE FROM members');
    echo "🧹 Cleared existing member data\n\n";
    
    // Reset auto-increment
    $pdo->exec('ALTER TABLE members AUTO_INCREMENT = 1');
    
    // Create exactly 12 dummy members as specified:
    // 7 males, 2 females, 3 others
    $dummyMembers = [
        // 7 Males
        ['Mohammad Rahman', '22341001', 'mohammad.rahman@g.bracu.ac.bd', 'Computer Science and Engineering', '+8801711111001', '5th', 'Male', '2001-01-15', 'Current Member', 'Music', 'Male', 'I want to explore my singing talent and perform on stage.'],
        ['Ahmed Hassan', '22341002', 'ahmed.hassan@g.bracu.ac.bd', 'Electrical and Electronic Engineering', '+8801711111002', '6th', 'Male', '2000-08-22', 'Current Member', 'Dance', 'Male', 'Hip-hop dancing is my passion and I want to showcase it.'],
        ['Rafiul Islam', '22341003', 'rafiul.islam@g.bracu.ac.bd', 'Business Administration', '+8801711111003', '4th', 'Male', '2001-12-10', 'Current Member', 'Drama', 'Male', 'Acting has always been my dream and I want to pursue it seriously.'],
        ['Shakib Khan', '22341004', 'shakib.khan@g.bracu.ac.bd', 'Architecture', '+8801711111004', '3rd', 'Male', '2002-05-18', 'New Member', 'Art', 'Male', 'I love digital art and want to learn traditional painting techniques.'],
        ['Tanvir Ahmed', '22341005', 'tanvir.ahmed@g.bracu.ac.bd', 'Mathematics and Natural Sciences', '+8801711111005', '7th', 'Male', '2000-03-25', 'Current Member', 'Poetry', 'Male', 'Writing poetry helps me express my thoughts and I want to share them.'],
        ['Karim Uddin', '22341006', 'karim.uddin@g.bracu.ac.bd', 'Economics and Social Sciences', '+8801711111006', '2nd', 'Male', '2003-09-14', 'New Member', 'Music', 'Male', 'I play guitar and want to form a band with other club members.'],
        ['Nasir Hossain', '22341007', 'nasir.hossain@g.bracu.ac.bd', 'English and Humanities', '+8801711111007', '5th', 'Male', '2001-07-30', 'Current Member', 'Dance', 'Male', 'Contemporary dance is my specialty and I want to choreograph performances.'],
        
        // 2 Females
        ['Fatima Khatun', '22341008', 'fatima.khatun@g.bracu.ac.bd', 'Computer Science and Engineering', '+8801711111008', '4th', 'Female', '2001-11-12', 'Current Member', 'Drama', 'Female', 'Theater performance is my passion and I want to direct plays.'],
        ['Ayesha Siddique', '22341009', 'ayesha.siddique@g.bracu.ac.bd', 'Pharmacy', '+8801711111009', '6th', 'Female', '2000-04-08', 'Current Member', 'Art', 'Female', 'I specialize in watercolor painting and want to exhibit my work.'],
        
        // 3 Others
        ['Jordan Smith', '22341010', 'jordan.smith@g.bracu.ac.bd', 'International Business', '+8801711111010', '3rd', 'Other', '2002-02-28', 'New Member', 'Poetry', 'Other', 'I write inclusive poetry and want to create a safe space for expression.'],
        ['Riley Chen', '22341011', 'riley.chen@g.bracu.ac.bd', 'Biotechnology', '+8801711111011', '5th', 'Other', '2001-10-17', 'Current Member', 'Music', 'Other', 'I compose instrumental music and want to collaborate with diverse artists.'],
        ['Sam Taylor', '22341012', 'sam.taylor@g.bracu.ac.bd', 'Environmental Science', '+8801711111012', '4th', 'Other', '2001-06-23', 'Current Member', 'Dance', 'Other', 'I practice contemporary dance and want to explore cultural fusion performances.']
    ];
    
    $hashedPassword = password_hash('member123', PASSWORD_DEFAULT);
    
    foreach ($dummyMembers as $data) {
        $stmt = $pdo->prepare("INSERT INTO members (
            full_name, university_id, email, password, department, phone, 
            semester, gender, date_of_birth, membership_status, event_category, gender_tracking, motivation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data[0], $data[1], $data[2], $hashedPassword, $data[3], $data[4],
            $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]
        ]);
    }
    
    echo "✅ Added 12 dummy members with specified gender distribution\n\n";
    
    // Show gender distribution
    $stmt = $pdo->query("SELECT gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY gender_tracking ORDER BY gender_tracking");
    $genderResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "👥 Gender Distribution Summary:\n";
    echo "===============================\n";
    foreach ($genderResults as $row) {
        $icon = $row['gender_tracking'] === 'Male' ? '♂️' : ($row['gender_tracking'] === 'Female' ? '♀️' : '⚧️');
        echo "{$icon} {$row['gender_tracking']}: {$row['count']} members\n";
    }
    
    // Show distribution by event category
    $stmt = $pdo->query("SELECT event_category, gender_tracking, COUNT(*) as count FROM members WHERE status = 'active' GROUP BY event_category, gender_tracking ORDER BY event_category, gender_tracking");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n📊 Gender Distribution by Event Category:\n";
    echo "========================================\n";
    foreach ($results as $row) {
        $icon = $row['gender_tracking'] === 'Male' ? '📘' : ($row['gender_tracking'] === 'Female' ? '🌸' : '🟪');
        echo "{$icon} {$row['event_category']} - {$row['gender_tracking']}: {$row['count']}\n";
    }
    
    echo "\n🎉 Dummy data creation completed!\n";
    echo "=================================\n";
    echo "✅ 7 Male members created\n";
    echo "✅ 2 Female members created\n";
    echo "✅ 3 Other members created\n";
    echo "✅ Total: 12 members\n\n";
    
    echo "🔗 Next Steps:\n";
    echo "- View Admin Dashboard: http://localhost/dashboard/bucuc/admin-login.php\n";
    echo "- Test Sign Up Form: http://localhost/dashboard/bucuc/index.php#footer\n";
    echo "- Login credentials: admin@bucuc.com / admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "\n💡 Solution: Run 'php create_members_table.php' first to create the database and table.\n";
    }
}
?>
