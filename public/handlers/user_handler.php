<?php

/**
 * User Handler
 * Processes login and registration requests
 */

// Initialize the app
include_once '../../src/common/initialization.php';

// Include the User class
include_once '../../src/classes/User.php';

// Initialize the results
$server_results = ['status' => 'success', 'message' => ''];

// Make sure user verb was passed
if (!isset($_POST['user-verb'])) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No action specified!';
}

// Make sure a token value was passed
elseif (!isset($_POST['token'])) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: Invalid action!';
}

// Make sure the token is legit (session token needs to match POST token)
elseif ($_SESSION['token'] !== $_POST['token']) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Timeout Error! Please refresh the page and try again.';
}

// If we get this far, all is well, so go for it
else {
    // Create a new User object
    $user = new User($mysqli);

    // Pass the user verb to the appropriate method
    switch ($_POST['user-verb']) {
        // Register a new user
        case 'register':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $distance_unit = $_POST['distance-unit'] ?? 'miles';

            // Validate password confirmation if provided
            if (isset($_POST['password-confirm'])) {
                if ($password !== $_POST['password-confirm']) {
                    $server_results['status'] = 'error';
                    $server_results['message'] = 'Error: Passwords do not match';
                    break;
                }
            }

            $server_results = $user->registerUser($username, $password, $distance_unit);
            break;

        // Login user
        case 'login':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $server_results = $user->loginUser($username, $password);

            // If login successful, set session variables
            if ($server_results['status'] === 'success' && isset($server_results['user'])) {
                $_SESSION['user_id'] = $server_results['user']['user_id'];
                $_SESSION['username'] = $server_results['user']['username'];
                $_SESSION['distance_unit'] = $server_results['user']['distance_unit'];
                $_SESSION['log_id'] = $server_results['user']['log_id'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);
            }
            break;

        // Logout user
        case 'logout':
            // Destroy session
            $_SESSION = [];
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            session_destroy();
            
            $server_results['status'] = 'success';
            $server_results['message'] = 'Logged out successfully';
            break;

        default:
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Unknown action!';
    }
}

// Create and output the JSON data
$JSON_data = json_encode($server_results, JSON_THROW_ON_ERROR | JSON_HEX_APOS | JSON_HEX_QUOT);
echo $JSON_data;

