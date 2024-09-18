/*
 * @Author: AJ Javadi 
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-09-18 17:35:45 
 * @Last Modified by: AJ Javadi
 * @Last Modified time: 2024-09-18 18:08:08
 * @Description: file:///Applications/XAMPP/xamppfiles/htdocs/footpower/private/common/initialization.php
 * Back-end chores for web app initialization
 */

 <?php

    //  make sure we see all the errors and warnings
    error_reporting(E_ALL | E_STRICT);

    // start a session
    session_start();


    // have we not created a token for this session, or has the token expired?
    if (!isset($_SESSION['token']) || time() > $_SESSION['token_expires']) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
        $_SESSION['token_expires'] = time() + 900; // 15 minutes
        $_SESSION['log_id'] = 1;
    }

    // include the app constants
    include_once './constants.php';

    // connect to the database
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    var_dump($mysqli);