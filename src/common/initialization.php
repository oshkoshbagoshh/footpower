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


// have we not created a token for this session, or has the token expired?
if (!isset($_SESSION['token']) || time() > $_SESSION['token_expires']) {
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    $_SESSION['token_expires'] = time() + 900;
    $_SESSION['log_id'] = 1; // temporary - will change this to user's log ID value when signed in

}

// include the app constants
include_once 'constants.php';

// Connect to the database
$mysqli = new MySQLi(HOST, USER, PASSWORD, DATABASE, PORT);

// check for an error
if ($mysqli->connect_error) {
    echo 'Connection failed! \n 
    Error #' . $mysqli->connect_errno . ': ' . $mysqli->connect_error;
    exit(0);
}





