<?php
session_start();
require_once 'Database/db.php';

$database = new Database();
$conn = $database->createConnection();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        try {
            // Check if user exists in members table
            $sql = "SELECT * FROM members WHERE email = :email AND status = 'active'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $member = $stmt->fetch();
            
            if ($member && password_verify($password, $member['password'])) {
                // Login successful
                $_SESSION['member_id'] = $member['id'];
                $_SESSION['member_name'] = $member['full_name'];
                $_SESSION['member_email'] = $member['email'];
                $_SESSION['member_department'] = $member['department'];
                $_SESSION['member_status'] = $member['membership_status'];
                $_SESSION['logged_in'] = true;
                
                $success = "Login successful! Welcome back, " . $member['full_name'];
                
                // Redirect to dashboard or profile page
                header("refresh:2;url=member_dashboard.php");
            } else {
                $error = "Invalid email or password";
            }
        } catch (PDOException $e) {
            $error = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - BRAC University Cultural Club</title>
    
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-festava-live.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <style>
        :root {
            --primary-orange: #e76f2c;
            --primary-yellow: #f3d35c;
            --dark-blue: #0a1931;
            --medium-blue: #1a2639;
            --light-blue: #2d3748;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--medium-blue) 50%, var(--light-blue) 100%);
            min-height: 100vh;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-yellow));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #fff;
        }
        
        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            color: #ccc;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 15px 20px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-yellow);
            box-shadow: 0 0 20px rgba(243, 211, 92, 0.3);
            outline: none;
        }
        
        .form-control::placeholder {
            color: #ccc;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-yellow));
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(231, 111, 44, 0.4);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: rgba(81, 207, 102, 0.2);
            border: 1px solid rgba(81, 207, 102, 0.3);
            color: #51cf66;
        }
        
        .alert-danger {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            color: var(--primary-orange);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .register-link a {
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            color: var(--primary-orange);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-users"></i>
                </div>
                <h1 class="login-title">Member Login</h1>
                <p class="login-subtitle">Access your Cultural Club account</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" 
                           class="form-control" 
                           name="email" 
                           placeholder="Enter your email address" 
                           required>
                </div>
                
                <div class="form-group">
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           placeholder="Enter your password" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Login to Account
                </button>
            </form>
            
            <div class="register-link">
                <p>Don't have an account? 
                    <a href="index.php#footer">Sign up here</a>
                </p>
            </div>
            
            <div class="back-link">
                <a href="index.php">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Main Site
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html> 