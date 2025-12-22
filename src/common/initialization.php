<?php

/***
 *  Create backend initialization file
 *  set the error reporting level
 *  start a session for the current user, if one hasn't been started already
 *  create a token for the session
 *  include common files, such as file constants throughout the app
 *  connecting to a datababase, if the app uses server data
 */

// make sure we see all errors and warnings
// DEV MODE error reporting
error_reporting(E_ALL );
// LIVE MODE Error Reporting
//ini_set('display_errors', 0);
//ini_set('log_errors', 1);
//ini_set('error_log', '../private/logs/error_log');


// start a session
session_start();


// include the app constants
include_once 'constants.php';

// Include autoloader for classes
include_once 'autoload.php';

// Connect to the database
$mysqli = new MySQLi(HOST, USER, PASSWORD, DATABASE, PORT);

// check for an error
if ($mysqli->connect_error) {
    echo 'Connection failed! \n 
    Error #' . $mysqli->connect_errno . ': ' . $mysqli->connect_error;
    exit(0);
}

// have we not created a token for this session, or has the token expired?
if (!isset($_SESSION['token']) || time() > $_SESSION['token_expires']) {
    $_SESSION['token'] = bin2hex('session&strong');
    $_SESSION['token_expires'] = time() + 900;
}

// Set log_id from session if user is logged in, otherwise use temporary value
if (!isset($_SESSION['log_id'])) {
    if (isset($_SESSION['user_id'])) {
        // User is logged in, but log_id not set - fetch it from database
        include_once __DIR__ . '/../classes/User.php';
        $user = new User($mysqli);
        $log_id = $user->getUserLogId($_SESSION['user_id']);
        $_SESSION['log_id'] = $log_id ?: 1;
    } else {
        $_SESSION['log_id'] = 1; // temporary - will change when user signs in
    }
}





