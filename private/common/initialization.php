<?php

/*
 * @Author: AJ Javadi 
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-03-10 03:56:40 
 * @Last Modified by: AJ Javadi
 * @Last Modified time: 2024-03-10 04:11:13
 * @Description: file:///Applications/MAMP/htdocs/footpower/private/common/initialization.php
 *  make sure we see all errors and warnings 
 */


// Report all errors and warnings
error_reporting(E_ALL | E_STRICT); 

/***
 *  //TODO: change this after testing is complete 
 *  set display errors to 0, logging of errors to 1 and set the error log to the private directory 
 */

// 


// 1.  start a session 
session_start();

// is this the first time the user is visiting the site?
if (!isset($_SESSION['token']) || time() > $_SESSION['token_expires']) {
    // generate a fresh token and store it in the session
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    $_SESSION['token_expires'] = time() + 900; // 15 minutes 
    $_SESSION['log_id'] = 1;
}

// =======================


// include the constants
include_once 'constants.php';

// =======================
// 2.  connect to the database
$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

// check for connection errors
if ($mysqli->connect_errno) {
    echo "Connection failed! 
    Error # "   . $mysqli->connect_errno . " : " . $mysqli->connect_error;
    exit(0);
}