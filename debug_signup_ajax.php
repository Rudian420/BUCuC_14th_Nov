<?php
session_start();

// For testing purposes, simulate admin session
$_SESSION['admin'] = true;
$_SESSION['username'] = 'test_admin';

// Include the signup status handler directly
require_once 'Action/signup_status_handler.php';
?>
