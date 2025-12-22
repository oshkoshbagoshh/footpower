<?php
/**
 * Logout Page
 * Handles user logout via GET request (simple redirect)
 */

include_once '../src/common/initialization.php';

// Include the User class
include_once '../src/classes/User.php';

// Create user handler instance to process logout
$user = new User($mysqli);

// Process logout via handler
$_POST['user-verb'] = 'logout';
$_POST['token'] = $_SESSION['token'] ?? '';

// Include handler logic (simplified)
$_SESSION = [];
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;

