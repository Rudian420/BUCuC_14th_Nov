<?php
require_once 'Database/db.php';

$database = new Database();
$conn = $database->createConnection();

echo "<h2>Gender Distribution Chart Debug</h2>";

try {
    // Check if members table exists
    $checkTable = "SHOW TABLES LIKE 'members'";
    $stmt = $conn->prepare($checkTable);
    $stmt->execute();
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "<p style='color: red;'>❌ Members table doesn't exist!</p>";
        
        // Create members table
        $createTable = "
        CREATE TABLE IF NOT EXISTS `members` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(100) NOT NULL,
            `university_id` VARCHAR(50) NOT NULL UNIQUE,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `gsuite_email` VARCHAR(100) NULL,
            `password` VARCHAR(255) NOT NULL,
            `department` VARCHAR(100) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `semester` VARCHAR(20) NOT NULL,
            `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
            `date_of_birth` DATE NOT NULL,
            `facebook_url` VARCHAR(255) NULL,
            `membership_status` ENUM('New Member', 'Current Member', 'Previous Member') NOT NULL,
            `event_category` VARCHAR(100) NULL,
            `gender_tracking` ENUM('Male', 'Female', 'Other') NULL,
            `motivation` TEXT NOT NULL,
            `status` ENUM('active', 'inactive', 'pending') DEFAULT 'active',
            `application_status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'accepted',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $conn->exec($createTable);
        echo "<p style='color: green;'>✅ Members table created successfully!</p>";
    } else {
        echo "<p style='color: green;'>✅ Members table exists</p>";
    }
    
    // Check current data
    $countSql = "SELECT COUNT(*) as total FROM members WHERE status = 'active'";
    $countStmt = $conn->prepare($countSql);
    $countStmt->execute();
    $totalMembers = $countStmt->fetch()['total'];
    
    echo "<p><strong>Current active members:</strong> $totalMembers</p>";
    
    if ($totalMembers == 0) {
        echo "<p style='color: orange;'>⚠️ No members found. Creating dummy data...</p>";
        
        // Insert dummy members
        $dummyMembers = [
            [
                'name' => 'John Doe',
                'id' => '2021001001',
                'email' => 'john.doe@bracu.ac.bd',
                'department' => 'Computer Science',
                'gender' => 'Male',
                'category' => 'Music'
            ],
            [
                'name' => 'Jane Smith',
                'id' => '2021001002',
                'email' => 'jane.smith@bracu.ac.bd',
                'department' => 'English Literature',
                'gender' => 'Female',
                'category' => 'Dance'
            ],
            [
                'name' => 'Alex Johnson',
                'id' => '2021001003',
                'email' => 'alex.johnson@bracu.ac.bd',
                'department' => 'Fine Arts',
                'gender' => 'Other',
                'category' => 'Drama'
            ],
            [
                'name' => 'Sarah Wilson',
                'id' => '2021001004',
                'email' => 'sarah.wilson@bracu.ac.bd',
                'department' => 'Psychology',
                'gender' => 'Female',
                'category' => 'Music'
            ],
            [
                'name' => 'Mike Brown',
                'id' => '2021001005',
                'email' => 'mike.brown@bracu.ac.bd',
                'department' => 'Business',
                'gender' => 'Male',
                'category' => 'Poetry'
            ],
            [
                'name' => 'Lisa Chen',
                'id' => '2021001006',
                'email' => 'lisa.chen@bracu.ac.bd',
                'department' => 'Architecture',
                'gender' => 'Female',
                'category' => 'Art'
            ],
            [
                'name' => 'David Garcia',
                'id' => '2021001007',
                'email' => 'david.garcia@bracu.ac.bd',
                'department' => 'Engineering',
                'gender' => 'Male',
                'category' => 'Dance'
            ],
            [
                'name' => 'Emma Taylor',
                'id' => '2021001008',
                'email' => 'emma.taylor@bracu.ac.bd',
                'department' => 'Mathematics',
                'gender' => 'Female',
                'category' => 'Music'
            ]
        ];
        
        $insertSql = "INSERT INTO members (
            full_name, university_id, email, password, department, phone, semester, 
            gender, date_of_birth, membership_status, event_category, gender_tracking, 
            motivation, status, application_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $insertStmt = $conn->prepare($insertSql);
        
        foreach ($dummyMembers as $member) {
            $hashedPassword = password_hash('member123', PASSWORD_DEFAULT);
            
            $insertStmt->execute([
                $member['name'],
                $member['id'],
                $member['email'],
                $hashedPassword,
                $member['department'],
                '+88012345' . rand(67890, 99999),
                '7th',
                $member['gender'],
                '2000-01-01',
                'Current Member',
                $member['category'],
                $member['gender'], // gender_tracking
                'I love participating in cultural activities and want to contribute to the club.',
                'active',
                'accepted'
            ]);
        }
        
        echo "<p style='color: green;'>✅ Added " . count($dummyMembers) . " dummy members!</p>";
    }
    
    // Now check the gender distribution
    echo "<h3>Gender Distribution Data:</h3>";
    
    $genderSql = "SELECT 
                    gender_tracking,
                    event_category,
                    COUNT(*) as count
                FROM members 
                WHERE status = 'active'
                GROUP BY gender_tracking, event_category
                ORDER BY gender_tracking, event_category";
    
    $genderStmt = $conn->prepare($genderSql);
    $genderStmt->execute();
    $genderData = $genderStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Gender</th><th>Event Category</th><th>Count</th></tr>";
    
    foreach ($genderData as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['gender_tracking']) . "</td>";
        echo "<td>" . htmlspecialchars($row['event_category']) . "</td>";
        echo "<td>" . $row['count'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test API endpoint
    echo "<h3>Testing API Endpoint:</h3>";
    
    // Simulate admin session for API test
    session_start();
    $_SESSION['admin'] = true;
    $_SESSION['admin_id'] = 1;
    
    // Get the API response
    $apiUrl = 'http://localhost/dashboard/bucuc/Action/gender_distribution_api.php';
    
    echo "<p><strong>API URL:</strong> $apiUrl</p>";
    
    // Simple curl test
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
    $apiResponse = curl_exec($ch);
    curl_close($ch);
    
    echo "<p><strong>API Response:</strong></p>";
    echo "<pre>" . htmlspecialchars($apiResponse) . "</pre>";
    
    // JavaScript to test the chart
    echo "<h3>Chart Test:</h3>";
    echo "<canvas id='testChart' width='400' height='400'></canvas>";
    
    echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
    echo "<script>
    const ctx = document.getElementById('testChart').getContext('2d');
    
    // Test data
    const testData = [
        {gender: 'Male', count: 3, color: '#36A2EB'},
        {gender: 'Female', count: 4, color: '#FF6384'},
        {gender: 'Other', count: 1, color: '#9966FF'}
    ];
    
    const labels = testData.map(item => item.gender);
    const counts = testData.map(item => item.count);
    const colors = testData.map(item => item.color);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#51cf66',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const gender = context.label;
                            const count = context.parsed;
                            const genderIcon = gender === 'Male' ? '♂' : gender === 'Female' ? '♀' : '⚧';
                            return gender + ': ' + count + ' ' + genderIcon;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    </script>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
