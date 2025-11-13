<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

require_once '../Database/db.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$confirmation = $input['confirmation'] ?? '';

// Double confirmation check
if ($action !== 'clear_all_applications' || $confirmation !== 'CLEAR ALL APPLICATIONS') {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid action or confirmation text. Please type "CLEAR ALL APPLICATIONS" exactly.'
    ]);
    exit();
}

try {
    $database = new Database();
    $pdo = $database->createConnection();
    
   
    
    // Get count of records before deletion (for logging)
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM members");
    $totalRecords = $countStmt->fetch()['total'];
    
    if ($totalRecords == 0) {
       
        echo json_encode([
            'success' => false, 
            'message' => 'No applications to clear. The members table is already empty.'
        ]);
        exit();
    }
    
    $deleteStmt = $pdo->prepare("DELETE FROM members");
    $result = $deleteStmt->execute();
    
    if ($result) {
        $resetStmt = $pdo->prepare("ALTER TABLE members AUTO_INCREMENT = 1");
        $resetStmt->execute();
        
        
        echo json_encode([
            'success' => true, 
            'message' => "Successfully cleared {$totalRecords} application(s) from the database.",
            'records_deleted' => $totalRecords
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}


?>
