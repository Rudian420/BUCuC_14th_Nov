<?php
/**
 * Database Setup Script for BUCuC Enhanced Admin System
 * This script will set up the new enhanced database structure
 */

// Security check - remove this in production or add proper authentication
if (!isset($_GET['setup']) || $_GET['setup'] !== 'bucuc2025') {
    die('Access denied. Add ?setup=bucuc2025 to the URL to run setup.');
}

require_once 'Database/db.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BUCuC Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .credential { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎭 BUCuC Enhanced Database Setup</h1>
        <p>This script will set up the enhanced database structure for the BRAC University Cultural Club admin system.</p>";

try {
    $database = new Database();
    $conn = $database->createConnection();
    
    echo "<div class='success'>✅ Database connection successful!</div>";
    
    // Read and execute the complete database setup SQL
    $sqlFile = 'complete_database_setup.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: {$sqlFile}");
    }
    
    echo "<div class='step'>
        <h3>Step 1: Reading SQL Setup File</h3>
        <p>Loading: {$sqlFile}</p>
    </div>";
    
    $sql = file_get_contents($sqlFile);
    
    if ($sql === false) {
        throw new Exception("Failed to read SQL file");
    }
    
    echo "<div class='success'>✅ SQL file loaded successfully (" . number_format(strlen($sql)) . " characters)</div>";
    
    echo "<div class='step'>
        <h3>Step 2: Executing Database Setup</h3>
        <p>This may take a few moments...</p>
    </div>";
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && 
                   !preg_match('/^(--|\/\*|\*\/|\/\*!|SET|START|COMMIT)/', $stmt) &&
                   !preg_match('/^(DELIMITER|\/\*!40101)/', $stmt);
        }
    );
    
    $executed = 0;
    $errors = 0;
    
    // Disable foreign key checks temporarily
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    foreach ($statements as $statement) {
        if (trim($statement) === '') continue;
        
        try {
            $conn->exec($statement);
            $executed++;
        } catch (PDOException $e) {
            // Some errors are expected (like table already exists)
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate entry') === false) {
                echo "<div class='error'>❌ Error executing statement: " . htmlspecialchars($e->getMessage()) . "</div>";
                $errors++;
            }
        }
    }
    
    // Re-enable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "<div class='success'>✅ Database setup completed!</div>";
    echo "<div class='info'>📊 Executed {$executed} SQL statements with {$errors} errors (expected for existing data)</div>";
    
    echo "<div class='step'>
        <h3>Step 3: Verifying Database Structure</h3>
    </div>";
    
    // Verify tables exist
    $tables = ['adminpanel', 'members', 'application_settings', 'events', 'event_participants'];
    $existingTables = [];
    
    foreach ($tables as $table) {
        try {
            $stmt = $conn->query("SELECT COUNT(*) FROM {$table}");
            $count = $stmt->fetchColumn();
            $existingTables[] = $table;
            echo "<div class='success'>✅ Table '{$table}': {$count} records</div>";
        } catch (PDOException $e) {
            echo "<div class='error'>❌ Table '{$table}': Not found or inaccessible</div>";
        }
    }
    
    // Verify views exist
    $views = ['gender_distribution_view', 'event_gender_summary', 'member_statistics'];
    foreach ($views as $view) {
        try {
            $stmt = $conn->query("SELECT COUNT(*) FROM {$view}");
            $count = $stmt->fetchColumn();
            echo "<div class='success'>✅ View '{$view}': {$count} records</div>";
        } catch (PDOException $e) {
            echo "<div class='error'>❌ View '{$view}': Not found</div>";
        }
    }
    
    echo "<div class='step'>
        <h3>Step 4: Setup Complete! 🎉</h3>
        <p>Your enhanced BUCuC database is now ready to use.</p>
    </div>";
    
    echo "<div class='credential'>
        <h4>🔐 Default Login Credentials:</h4>
        <p><strong>Admin Login:</strong><br>
        Email: admin@bucuc.com<br>
        Password: admin123</p>
        
        <p><strong>Test Member Login:</strong><br>
        Email: john.doe@bracu.ac.bd<br>
        Password: member123</p>
    </div>";
    
    echo "<div class='info'>
        <h4>🚀 Next Steps:</h4>
        <ol>
            <li>Log in to the admin dashboard at <a href='admin-login.php'>admin-login.php</a></li>
            <li>Change the default admin password</li>
            <li>Review the member applications</li>
            <li>Configure application settings</li>
            <li>Add your email credentials in member_management.php for notifications</li>
        </ol>
    </div>";
    
    echo "<div class='step'>
        <h3>📋 Database Features Enabled:</h3>
        <ul>
            <li>✅ Enhanced member management with application tracking</li>
            <li>✅ Gender distribution analytics with views</li>
            <li>✅ Event category statistics</li>
            <li>✅ Admin role management (main_admin/admin)</li>
            <li>✅ Application system toggle</li>
            <li>✅ Email notifications for accepted members</li>
            <li>✅ Audit logging for admin actions</li>
            <li>✅ Stored procedures for consistent operations</li>
            <li>✅ Database triggers for automated updates</li>
        </ul>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Setup failed: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<div class='info'>
        <h4>🔧 Troubleshooting:</h4>
        <ol>
            <li>Make sure your database server is running</li>
            <li>Check database credentials in Database/config.php</li>
            <li>Ensure your MySQL user has CREATE, ALTER, DROP privileges</li>
            <li>Try running the complete_database_setup.sql file manually in phpMyAdmin</li>
        </ol>
    </div>";
}

echo "</div></body></html>";
?>