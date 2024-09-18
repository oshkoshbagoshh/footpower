<?php
/**
 * @Author: AJ Javadi 
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-09-18 17:35:45 
 * @Last Modified by: AJ Javadi
 * @Last Modified time: 2024-09-18 18:34:01
 * @Description: Back-end chores for web app initialization
 */

// Ensure we're in the correct directory
chdir(__DIR__);

// Load environment variables
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Validate required environment variables
$required_env_vars = ['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME'];
foreach ($required_env_vars as $var) {
    if (!isset($_ENV[$var])) {
        die("Error: Environment variable $var is not set.");
    }
}

// Set error reporting
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? '0');
// TODO: when going live, change this to 
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log','../logs/error_log');

// Start or resume session
session_start();

// CSRF Token management
if (!isset($_SESSION['token']) || time() > $_SESSION['token_expires']) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['token_expires'] = time() + 900; // 15 minutes
}

// Include application constants
require_once './constants.php';

// Database connection
try {
    $mysqli = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed: " . $mysqli->connect_error);
    }

    // Set charset to UTF-8
    $mysqli->set_charset('utf8mb4');

} catch (Exception $e) {
    // Log the error and display a user-friendly message
    error_log($e->getMessage());
    die("We're experiencing technical difficulties. Please try again later.");
}

// Additional initialization code can be added here

// For debugging purposes only, remove in production
if ($_ENV['APP_DEBUG'] === 'true') {
    var_dump($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
}


